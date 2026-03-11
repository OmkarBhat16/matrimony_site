<?php

namespace App\Http\Controllers;

use App\Models\EditUserProfile;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Show the logged-in user's own profile page.
     * Redirects to onboarding if no profile exists yet.
     */
    public function myProfile()
    {
        $user = auth()->user();

        if (!$user->profile()->exists()) {
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

        // Delete any previous pending edit
        EditUserProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->delete();

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        EditUserProfile::create($validated);

        return redirect()->route('profile')->with('success', 'Your profile edit has been submitted for review.');
    }

    /**
     * Show the form for creating a new resource.
     * Allowed when verification_step is 'step1_complete'.
     */
    public function create()
    {
        $user = auth()->user();

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

        // Default primary image to 1
        $validated["primary_image"] = $validated["primary_image"] ?? 1;

        // Remove images from validated before creating the DB record
        $images = $request->file("images") ?? [];
        unset($validated["images"]);

        $profile = UserProfile::create($validated);

        // Store uploaded images
        $this->storeImages($profile, $images);

        // Move to step2_pending — profile is now under review
        $user->update(['verification_step' => 'step2_pending']);

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

        if (!$profile) {
            abort(404);
        }

        $slot = (int) $request->input("slot");

        // Only allow setting a slot that actually has an uploaded image
        if ($profile->imageUrl($slot) === null) {
            return back()->with("error", "No image uploaded for that slot.");
        }

        $profile->update(["primary_image" => $slot]);

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

        $this->storeImages($profile, $request->file("images"));

        return back()->with("success", "Photos updated successfully.");
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
     * Persist uploaded image files into storage/app/public/profiles/<email>/
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

        $folder = $profile->imageFolder();

        // Ensure directory exists (Storage::makeDirectory is a no-op if already present)
        Storage::disk("public")->makeDirectory($folder);

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

            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ["jpg", "jpeg", "png", "webp"])) {
                $ext = "jpg";
            }

            // Remove any old file for this slot (could be a different extension)
            foreach (["jpg", "jpeg", "png", "webp"] as $oldExt) {
                $oldPath = $folder . "/" . $slot . "." . $oldExt;
                if (Storage::disk("public")->exists($oldPath)) {
                    Storage::disk("public")->delete($oldPath);
                }
            }

            $file->storeAs($folder, $slot . "." . $ext, "public");
        }
    }
}
