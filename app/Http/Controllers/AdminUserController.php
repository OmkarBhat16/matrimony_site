<?php

namespace App\Http\Controllers;

use App\Models\EditUserProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Show users list with tabs based on verification_step.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'registrations');

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

        return view('admin.users', compact('tab', 'registrations', 'pendingReview', 'approved'));
    }

    /**
     * Generate a random password for a registered user (Step 1 verification).
     */
    public function createAccount(User $user)
    {
        if ($user->verification_step !== 'unverified') {
            return redirect()->back()->with('error', 'Account has already been created for this user.');
        }

        $plainPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($plainPassword),
            'verification_step' => 'step1_complete',
        ]);

        return redirect()
            ->back()
            ->with('generated_password', $plainPassword)
            ->with('generated_for_user', $user->id);
    }

    /**
     * Show a user's full profile for admin review.
     */
    public function showProfile(User $user)
    {
        $user->load('profile');

        return view('admin.profile-review', compact('user'));
    }

    /**
     * Final approval — mark profile as approved (Step 2 verification complete).
     */
    public function approve(User $user)
    {
        $user->update([
            'verification_step' => 'approved',
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

        return view('admin.edit-review', compact('edit', 'currentProfile', 'diff'));
    }

    /**
     * Approve an edit: copy changed fields to user_profile, delete the edit row.
     */
    public function approveEdit(EditUserProfile $edit)
    {
        $profile = UserProfile::where('user_id', $edit->user_id)->firstOrFail();

        // Copy all diffable fields from edit to the real profile
        $data = [];
        foreach (EditUserProfile::DIFFABLE_FIELDS as $field => $label) {
            $data[$field] = $edit->{$field};
        }
        $profile->update($data);

        $edit->delete();

        return redirect()->route('admin.pending-edits')->with('success', 'Profile edit approved and applied.');
    }

    /**
     * Reject an edit: just delete the edit row.
     */
    public function rejectEdit(EditUserProfile $edit)
    {
        $edit->delete();

        return redirect()->route('admin.pending-edits')->with('success', 'Profile edit rejected.');
    }
}
