<?php

namespace App\Http\Controllers;

use App\Models\EditUserProfile;
use App\Models\UserProfile;
use App\Services\ProfileImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function __construct(private ProfileImageManager $images)
    {
    }

    /**
     * Show the logged-in user's own profile page.
     * Redirects to onboarding if no profile exists yet.
     */
    public function myProfile()
    {
        $user = auth()->user();

        Log::debug('User profile page requested.', [
            'user_id' => $user->id,
            'verification_step' => $user->verification_step,
        ]);

        if (!$user->profile()->exists()) {
            Log::info('User redirected to onboarding because no profile exists.', [
                'user_id' => $user->id,
                'verification_step' => $user->verification_step,
            ]);

            return redirect()
                ->route("onboarding.create")
                ->with("info", "Please complete your profile to get started.");
        }

        $pendingEdit = EditUserProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view("profile.profile", compact('pendingEdit'));
    }

    /**
     * Show the edit form, pre-filled with current profile data.
     */
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        Log::debug('User opened profile edit page.', [
            'user_id' => $user->id,
            'verification_step' => $user->verification_step,
            'has_profile' => (bool) $profile,
        ]);

        if (!$profile) {
            return redirect()->route('onboarding.create');
        }

        // If there's already a pending edit, load those values instead
        $pendingEdit = EditUserProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        $values = $pendingEdit ?? $profile;

        return view('profile.edit', compact('profile', 'values', 'pendingEdit'));
    }

    /**
     * Save the edit request to the edit_user_profiles table (not directly to user_profile).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'full_name'             => ['nullable', 'string', 'max:255'],
            'navras_naav'           => ['nullable', 'string', 'max:255'],
            'gender'                => ['nullable', 'in:male,female,other'],
            'education'             => ['nullable', 'string', 'max:255'],
            'occupation'            => ['nullable', 'string', 'max:255'],
            'annual_income'         => ['nullable', 'numeric'],
            'date_of_birth'         => ['nullable', 'date'],
            'day_and_time_of_birth' => ['nullable', 'string', 'max:255'],
            'place_of_birth'        => ['nullable', 'string', 'max:255'],
            'jaath'                 => ['nullable', 'string', 'max:255'],
            'height_cm__Oonchi'     => ['nullable', 'string', 'max:255'],
            'skin_complexion__Rang' => ['nullable', 'string', 'max:255'],
            'zodiac_sign__Raas'     => ['nullable', 'string', 'max:255'],
            'naadi'                 => ['nullable', 'string', 'max:255'],
            'gann'                  => ['nullable', 'string', 'max:255'],
            'devak'                 => ['nullable', 'string', 'max:255'],
            'kul_devata'            => ['nullable', 'string', 'max:255'],
            'fathers_name'          => ['nullable', 'string', 'max:255'],
            'mothers_name'          => ['nullable', 'string', 'max:255'],
            'marital_status'        => ['nullable', 'string', 'max:255'],
            'siblings'              => ['nullable', 'string'],
            'uncles'                => ['nullable', 'string'],
            'aunts'                 => ['nullable', 'string'],
            'mumbai_address'        => ['nullable', 'string'],
            'village_address'       => ['nullable', 'string'],
            'village_farm'          => ['nullable', 'string', 'max:255'],
            'naathe_relationships'  => ['nullable', 'string'],
        ]);

        $user = auth()->user();

        Log::debug('User profile edit submission received.', [
            'user_id' => $user->id,
            'verification_step' => $user->verification_step,
            'field_count' => count(array_filter($validated, fn ($value) => !is_null($value) && $value !== '')),
        ]);

        $existingPending = EditUserProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        $imageChanges = $existingPending?->image_changes ?? [];

        try {
            if ($existingPending) {
                $existingPending->delete();
            }

            $validated['user_id'] = $user->id;
            $validated['edit_type'] = 'profile';
            $validated['status'] = 'pending';
            $validated['image_changes'] = empty($imageChanges) ? null : $imageChanges;

            $edit = EditUserProfile::create($validated);
        } catch (\Throwable $e) {
            Log::error('User profile edit submission failed.', [
                'user_id' => $user->id,
                'verification_step' => $user->verification_step,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('profile')->with('error', 'Your profile edit could not be submitted.');
        }

        Log::info('User profile edit submitted for review.', [
            'user_id' => $user->id,
            'edit_id' => $edit->id,
            'verification_step' => $user->verification_step,
        ]);

        return redirect()->route('profile')->with('success', 'Your profile edit has been submitted for review.');
    }

    /**
     * Show the form for creating a new resource.
     * Allowed when verification_step is 'step1_complete'.
     */
    public function create()
    {
        $user = auth()->user();

        Log::debug('User opened onboarding page.', [
            'user_id' => $user->id,
            'verification_step' => $user->verification_step,
            'has_profile' => $user->profile()->exists(),
        ]);

        if ($user->profile()->exists()) {
            return redirect()->route("root.matrimony");
        }

        if (!$user->needsOnboarding()) {
            if ($user->isPendingReview()) {
                return redirect("/pending-review");
            }
            return redirect()
                ->route("root.matrimony")
                ->with("error", "You cannot access onboarding at this time.");
        }

        return view("onboarding.create");
    }

    /**
     * Store a newly created resource in storage.
     * Sets verification_step to 'step2_pending' after profile creation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "full_name" => ["nullable", "string", "max:255"],
            "navras_naav" => ["nullable", "string", "max:255"],
            "gender" => ["nullable", "in:male,female,other"],
            "education" => ["nullable", "string", "max:255"],
            "occupation" => ["nullable", "string", "max:255"],
            "annual_income" => ["nullable", "numeric"],
            "date_of_birth" => ["nullable", "date"],
            "day_and_time_of_birth" => ["nullable", "string", "max:255"],
            "place_of_birth" => ["nullable", "string", "max:255"],
            "jaath" => ["nullable", "string", "max:255"],
            "height_cm__Oonchi" => ["nullable", "string", "max:255"],
            "skin_complexion__Rang" => ["nullable", "string", "max:255"],
            "zodiac_sign__Raas" => ["nullable", "string", "max:255"],
            "naadi" => ["nullable", "string", "max:255"],
            "gann" => ["nullable", "string", "max:255"],
            "devak" => ["nullable", "string", "max:255"],
            "kul_devata" => ["nullable", "string", "max:255"],
            "fathers_name" => ["nullable", "string", "max:255"],
            "mothers_name" => ["nullable", "string", "max:255"],
            "marital_status" => ["nullable", "string", "max:255"],
            "siblings" => ["nullable", "string"],
            "uncles" => ["nullable", "string"],
            "aunts" => ["nullable", "string"],
            "mumbai_address" => ["nullable", "string"],
            "village_address" => ["nullable", "string"],
            "village_farm" => ["nullable", "string", "max:255"],
            "naathe_relationships" => ["nullable", "string"],
            // Images: up to 3, each max 5 MB, jpg/png/webp
            "images" => ["nullable", "array", "max:3"],
            "images.*" => [
                "nullable",
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:5120",
            ],
            "primary_image" => ["nullable", "integer", "in:1,2,3"],
        ]);

        $user = Auth::user();
        $validated["user_id"] = $user->id;

        Log::debug('User onboarding submission received.', [
            'user_id' => $user->id,
            'verification_step' => $user->verification_step,
            'image_count' => count($request->file('images') ?? []),
        ]);

        // Default primary image to 1
        $validated["primary_image"] = $validated["primary_image"] ?? 1;

        // Remove images from validated before creating the DB record
        $images = $request->file("images") ?? [];
        unset($validated["images"]);

        try {
            $profile = DB::transaction(function () use ($validated, $user) {
                $profile = UserProfile::create($validated);

                $user->forceFill([
                    'verification_step' => 'step2_pending',
                ])->save();

                return $profile;
            }, 5);

            // Store uploaded images after the DB transaction commits successfully.
            $this->storeImages($profile, $images);
        } catch (\Throwable $e) {
            Log::error('User onboarding submission failed.', [
                'user_id' => $user->id,
                'verification_step' => $user->verification_step,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Profile submission failed. Please try again.');
        }

        Log::info('User onboarding submitted for admin review.', [
            'user_id' => $user->id,
            'profile_id' => $profile->id,
            'verification_step' => $user->fresh()->verification_step,
            'image_count' => count($images),
        ]);

        return redirect("/pending-review");
    }

    /**
     * Set which slot (1, 2, or 3) is the primary image for the logged-in user's profile.
     */
    public function setPrimaryImage(Request $request)
    {
        $request->validate([
            "slot" => ["required", "integer", "in:1,2,3"],
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        Log::debug('User primary image change requested.', [
            'user_id' => $user->id,
            'requested_slot' => (int) $request->input('slot'),
            'has_profile' => (bool) $profile,
        ]);

        if (!$profile) {
            abort(404);
        }

        $slot = (int) $request->input("slot");

        // Only allow setting a slot that actually has an uploaded image
        if ($profile->imageUrl($slot) === null) {
            Log::info('User primary image change rejected because slot has no image.', [
                'user_id' => $user->id,
                'requested_slot' => $slot,
            ]);

            return back()->with("error", "No image uploaded for that slot.");
        }

        try {
            $profile->update(["primary_image" => $slot]);
        } catch (\Throwable $e) {
            Log::error('User primary image change failed.', [
                'user_id' => $user->id,
                'requested_slot' => $slot,
                'error' => $e->getMessage(),
            ]);

            return back()->with("error", "Primary photo could not be updated.");
        }

        Log::info('User primary image updated.', [
            'user_id' => $user->id,
            'primary_image' => $slot,
        ]);

        return back()->with("success", "Primary photo updated.");
    }

    /**
     * Upload new images to an existing profile (slot 1–3).
     * Called from the profile management page.
     */
    public function uploadImages(Request $request)
    {
        $request->validate([
            "images" => ["required", "array", "max:3"],
            "images.*" => [
                "required",
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:5120",
            ],
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            abort(404);
        }

        $images = $request->file("images") ?? [];
        $pendingEdit = EditUserProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        $imageChanges = $pendingEdit?->image_changes ?? [];

        foreach ($images as $index => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $slot = (int) $index;
            if ($slot === 0) {
                $slot = 1;
            }

            if ($slot < 1 || $slot > 3) {
                continue;
            }

            $hasPublishedImage = $this->images->hasCurrentImage($profile, $slot);
            $hasPendingReplacement = $this->images->hasPendingImage($profile, $slot);

            if ($hasPublishedImage || $hasPendingReplacement) {
                $this->images->storePendingImage($profile, $slot, $file);
                $imageChanges[(string) $slot] = true;
            } else {
                $this->images->storeCurrentImage($profile, $slot, $file);
                unset($imageChanges[(string) $slot]);
            }
        }

        $hasPendingReplacements = !empty($imageChanges);

        if ($hasPendingReplacements) {
            if ($pendingEdit) {
                $pendingEdit->update([
                    'image_changes' => $imageChanges,
                ]);
            } else {
                EditUserProfile::create([
                    'user_id' => $user->id,
                    'edit_type' => 'image',
                    'status' => 'pending',
                    'image_changes' => $imageChanges,
                ]);
            }
        }

        $message = $hasPendingReplacements
            ? "Your replacement photo has been submitted for admin approval."
            : "Photos updated successfully.";

        return back()->with("success", $message);
    }

    /**
     * Serve a stored profile image from resources/assets/<phone>/<slot>.<ext>.
     */
    public function showImage(UserProfile $userProfile, int $slot)
    {
        abort_unless(in_array($slot, [1, 2, 3], true), 404);

        $path = $userProfile->imagePath($slot);

        if ($path !== null) {
            return response()->file($path);
        }

        abort(404);
    }

    /**
     * Serve a pending replacement image for admin review.
     */
    public function showPendingImage(UserProfile $userProfile, int $slot)
    {
        abort_unless(in_array($slot, [1, 2, 3], true), 404);

        $path = $userProfile->pendingImagePath($slot);

        if ($path !== null && is_file($path)) {
            return response()->file($path);
        }

        abort(404);
    }

    /**
     * Display the specified resource (public profile view).
     */
    public function show(UserProfile $userProfile): \Illuminate\View\View
    {
        $user = auth()->user();

        if (!$user->isApproved() || !$user->profile()->exists()) {
            abort(
                403,
                "You must be approved and have a profile to view other profiles.",
            );
        }

        $userProfile->load("user");

        return view("profile.show", [
            "profile" => $userProfile,
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Persist uploaded image files into resources/assets/<phone>/
     * naming them 1.ext, 2.ext, 3.ext based on the array index (1-based).
     * Any pre-existing file in that slot is deleted first.
     *
     * @param  UserProfile        $profile
     * @param  \Illuminate\Http\UploadedFile[]  $images  Indexed from 0 or 1
     */
    private function storeImages(UserProfile $profile, array $images): void
    {
        if (empty($images)) {
            return;
        }

        foreach ($images as $index => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            // Slots are 1-based; form sends images[1], images[2], images[3]
            $slot = is_int($index) ? $index : (int) $index;
            // If the form uses 0-based keys, shift up
            if ($slot === 0) {
                $slot = 1;
            }
            // Clamp to valid range
            if ($slot < 1 || $slot > 3) {
                continue;
            }

            $this->images->storeCurrentImage($profile, $slot, $file);
        }
    }
}
