<?php

namespace App\Http\Controllers;

use App\Models\EditUserProfile;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\ProfileImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function __construct(private ProfileImageManager $images)
    {
    }

    /**
     * Show users list with tabs based on verification_step.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'registrations');
        $stepFilter = $request->query('step', 'all');
        $search = trim((string) $request->query('search', ''));

        $registrations = User::where('verification_step', 'unverified')
            ->where('role', 'user')
            ->latest()
            ->get();

        $pendingReview = User::where('verification_step', 'step2_pending')
            ->where('role', 'user')
            ->latest()
            ->get();

        $approved = User::where('verification_step', 'approved')
            ->where('role', 'user')
            ->latest()
            ->get();

        $pendingEditsByUser = EditUserProfile::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get()
            ->keyBy('user_id');

        $allUsers = User::query()
            ->where('role', 'user')
            ->when($stepFilter !== 'all', fn ($query) => $query->where('verification_step', $stepFilter))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.users', compact(
            'tab',
            'stepFilter',
            'search',
            'registrations',
            'pendingReview',
            'approved',
            'pendingEditsByUser',
            'allUsers',
        ));
    }

    /**
     * Generate a random password for a registered user (Step 1 verification).
     */
    public function createAccount(User $user)
    {
        Log::debug('Admin account creation requested.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'target_step' => $user->verification_step,
        ]);

        try {
            $result = DB::transaction(function () use ($user) {
                // Lock the row so MySQL/InnoDB cannot process two approvals for
                // the same user at the same time.
                $lockedUser = User::whereKey($user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedUser->verification_step !== 'unverified') {
                    Log::info('Admin account creation skipped because user is no longer unverified.', [
                        'admin_id' => auth()->id(),
                        'target_user_id' => $lockedUser->id,
                        'verification_step' => $lockedUser->verification_step,
                    ]);

                    return ['status' => 'already_created'];
                }

                $plainPassword = Str::random(12);

                $lockedUser->forceFill([
                    'password' => $plainPassword,
                    'verification_step' => 'step1_complete',
                ])->save();

                $lockedUser->refresh();

                if (! $lockedUser->needsOnboarding()) {
                    throw new \RuntimeException('Account approval did not reach step1_complete.');
                }

                Log::info('Admin account creation completed.', [
                    'admin_id' => auth()->id(),
                    'target_user_id' => $lockedUser->id,
                    'verification_step' => $lockedUser->verification_step,
                ]);

                return [
                    'status' => 'created',
                    'plain_password' => $plainPassword,
                ];
            }, 5);
        } catch (\Throwable $e) {
            Log::error('Failed to create matrimony account during admin approval.', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'verification_step' => $user->verification_step,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Account creation failed. No password was issued. Please try again.');
        }

        if ($result['status'] === 'already_created') {
            return redirect()
                ->route('admin.users', ['tab' => 'all', 'step' => 'step1_complete'])
                ->with('error', 'Account has already been created for this user.');
        }

        return redirect()
            ->route('admin.users', ['tab' => 'all', 'step' => 'step1_complete'])
            ->with('generated_password', $result['plain_password'])
            ->with('generated_for_user', $user->id)
            ->with('success', 'Password generated and account moved to onboarding.');
    }

    /**
     * Reset the password for an approved user and return the new plain password.
     */
    public function resetPassword(User $user)
    {
        Log::debug('Admin password reset requested.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'verification_step' => $user->verification_step,
        ]);

        if ($user->verification_step === 'unverified') {
            Log::info('Admin password reset denied because user account is still unverified.', [
                'admin_id' => auth()->id(),
                'target_user_id' => $user->id,
                'verification_step' => $user->verification_step,
            ]);

            return redirect()->back()->with('error', 'Password reset is only available after account creation.');
        }

        try {
            $plainPassword = Str::random(12);

            $user->update([
                'password' => $plainPassword,
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin password reset failed.', [
                'admin_id' => auth()->id(),
                'target_user_id' => $user->id,
                'verification_step' => $user->verification_step,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Password reset failed. Please try again.');
        }

        Log::info('Admin password reset completed.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'verification_step' => $user->verification_step,
        ]);

        return redirect()
            ->route('admin.users', ['tab' => 'all', 'step' => $user->verification_step])
            ->with('generated_password', $plainPassword)
            ->with('generated_for_user', $user->id)
            ->with('success', 'Password reset successfully.');
    }

    /**
     * Show a user's full profile for admin review.
     */
    public function showProfile(User $user)
    {
        $user->load('profile');

        Log::debug('Admin viewed user profile.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'verification_step' => $user->verification_step,
            'has_profile' => (bool) $user->profile,
        ]);

        return view('admin.profile-review', compact('user'));
    }

    /**
     * Final approval — mark profile as approved (Step 2 verification complete).
     */
    public function approve(User $user)
    {
        Log::debug('Admin final approval requested.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'from_step' => $user->verification_step,
        ]);

        try {
            $user->update([
                'verification_step' => 'approved',
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin final approval failed.', [
                'admin_id' => auth()->id(),
                'target_user_id' => $user->id,
                'from_step' => $user->verification_step,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'User approval failed. Please try again.');
        }

        Log::info('Admin final approval completed.', [
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'verification_step' => $user->verification_step,
        ]);

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    // -------------------------------------------------------------------------
    // Profile Edit Approval
    // -------------------------------------------------------------------------

    /**
     * List all pending edit requests.
     */
    public function pendingEdits()
    {
        $edits = EditUserProfile::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        Log::debug('Admin viewed pending edit list.', [
            'admin_id' => auth()->id(),
            'pending_count' => $edits->count(),
        ]);

        return view('admin.pending-edits', compact('edits'));
    }

    /**
     * Show the diff between current profile and edit request.
     */
    public function reviewEdit(EditUserProfile $edit)
    {
        $edit->load('user');
        $currentProfile = UserProfile::where('user_id', $edit->user_id)->firstOrFail();
        $diff = $edit->diff($currentProfile);
        $pendingImageSlots = $edit->pendingImageSlots();

        Log::debug('Admin reviewed pending edit.', [
            'admin_id' => auth()->id(),
            'edit_id' => $edit->id,
            'user_id' => $edit->user_id,
            'edit_type' => $edit->edit_type,
            'pending_image_slots' => $pendingImageSlots,
        ]);

        return view('admin.edit-review', compact('edit', 'currentProfile', 'diff', 'pendingImageSlots'));
    }

    /**
     * Approve an edit: copy changed fields to user_profile, delete the edit row.
     */
    public function approveEdit(EditUserProfile $edit)
    {
        Log::debug('Admin edit approval requested.', [
            'admin_id' => auth()->id(),
            'edit_id' => $edit->id,
            'user_id' => $edit->user_id,
            'edit_type' => $edit->edit_type,
        ]);

        $profile = UserProfile::where('user_id', $edit->user_id)->firstOrFail();

        try {
            $pendingImageSlots = $edit->pendingImageSlots();
            $shouldSkipLegacyBlankProfileUpdate = $edit->edit_type === 'profile'
                && !$edit->hasProfileFieldValues()
                && !empty($pendingImageSlots);

            if ($edit->edit_type !== 'image' && !$shouldSkipLegacyBlankProfileUpdate) {
                $profileDiff = $edit->diff($profile);

                if (!empty($profileDiff)) {
                    $data = [];
                    foreach (array_keys($profileDiff) as $field) {
                        $data[$field] = $edit->{$field};
                    }
                    $profile->update($data);
                }
            }

            foreach ($pendingImageSlots as $slot) {
                $this->images->approvePendingImage($profile, $slot);
            }

            $edit->delete();
        } catch (\Throwable $e) {
            Log::error('Admin edit approval failed.', [
                'admin_id' => auth()->id(),
                'edit_id' => $edit->id,
                'user_id' => $edit->user_id,
                'edit_type' => $edit->edit_type,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.pending-edits')->with('error', 'Profile edit approval failed.');
        }

        Log::info('Admin edit approval completed.', [
            'admin_id' => auth()->id(),
            'edit_id' => $edit->id,
            'user_id' => $edit->user_id,
            'edit_type' => $edit->edit_type,
        ]);

        return redirect()->route('admin.pending-edits')->with('success', 'Profile edit approved and applied.');
    }

    /**
     * Reject an edit: just delete the edit row.
     */
    public function rejectEdit(EditUserProfile $edit)
    {
        Log::debug('Admin edit rejection requested.', [
            'admin_id' => auth()->id(),
            'edit_id' => $edit->id,
            'user_id' => $edit->user_id,
            'edit_type' => $edit->edit_type,
        ]);

        $profile = UserProfile::where('user_id', $edit->user_id)->firstOrFail();

        try {
            foreach ($edit->pendingImageSlots() as $slot) {
                $this->images->rejectPendingImage($profile, $slot);
            }

            $edit->delete();
        } catch (\Throwable $e) {
            Log::error('Admin edit rejection failed.', [
                'admin_id' => auth()->id(),
                'edit_id' => $edit->id,
                'user_id' => $edit->user_id,
                'edit_type' => $edit->edit_type,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.pending-edits')->with('error', 'Profile edit rejection failed.');
        }

        Log::info('Admin edit rejection completed.', [
            'admin_id' => auth()->id(),
            'edit_id' => $edit->id,
            'user_id' => $edit->user_id,
            'edit_type' => $edit->edit_type,
        ]);

        return redirect()->route('admin.pending-edits')->with('success', 'Profile edit rejected.');
    }
}
