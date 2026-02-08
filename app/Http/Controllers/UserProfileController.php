<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->profile()->exists()) {
            return redirect()->route('root.matrimony');
        } else if (!auth()->user()->approved) {
            return redirect()->route('root.matrimony')->with('error', 'Your account is pending approval. Please wait for an administrator to approve your account before creating a profile.');
        }
        return view('onboarding.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Step 1: Personal Information
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'phone_number' => ['required', 'string', 'max:15', 'unique:user_profile,phone_number'],
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'bio' => ['required', 'string', 'max:1000'],

            // Step 2: Background & Location
            'religion' => ['required', 'string', 'max:255'],
            'caste' => ['required', 'string', 'max:255'],
            'mother_tongue' => ['required', 'string', 'max:255'],
            'education' => ['required', 'string', 'max:255'],
            'occupation' => ['required', 'string', 'max:255'],
            'annual_income' => ['required', 'numeric', 'min:0'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],

            // Step 3: Lifestyle & Preferences
            'height_cm' => ['required', 'integer', 'min:100', 'max:250'],
            'weight_kg' => ['required', 'numeric', 'min:30', 'max:200'],
            'dietary_preferences' => ['required', 'in:vegetarian,non-vegetarian,vegan'],
            'smoking_habits' => ['required', 'in:non-smoker,occasional,regular'],
            'drinking_habits' => ['required', 'in:non-drinker,occasional,regular'],
            'hobbies_interests' => ['required', 'string', 'max:1000'],
        ]);

        // Handle profile picture upload
        $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');

        // Create the user profile
        UserProfile::create([
            'user_id' => Auth::id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'marital_status' => $validated['marital_status'],
            'phone_number' => $validated['phone_number'],
            'profile_picture' => $profilePicturePath,
            'bio' => $validated['bio'],
            'religion' => $validated['religion'],
            'caste' => $validated['caste'],
            'mother_tongue' => $validated['mother_tongue'],
            'education' => $validated['education'],
            'occupation' => $validated['occupation'],
            'annual_income' => $validated['annual_income'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'height_cm' => $validated['height_cm'],
            'weight_kg' => $validated['weight_kg'],
            'dietary_preferences' => $validated['dietary_preferences'],
            'smoking_habits' => $validated['smoking_habits'],
            'drinking_habits' => $validated['drinking_habits'],
            'hobbies_interests' => $validated['hobbies_interests'],
        ]);

        return redirect('/matrimony')->with('success', 'Profile created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile): \Illuminate\View\View
    {
        $user = auth()->user();

        if (!$user->approved || !$user->profile()->exists()) {
            abort(403, 'You must be approved and have a profile to view other profiles.');
        }

        $userProfile->load('user');

        return view('profile.show', [
            'profile' => $userProfile,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }
}
