<?php

namespace App\Http\Controllers;

use App\Models\FeaturedProfile;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminFeaturedProfileController extends Controller
{
    public function index()
    {
        $search = trim((string) request()->query('search', ''));

        $featuredProfiles = FeaturedProfile::query()
            ->with(['userProfile.user'])
            ->latest()
            ->get();

        $searchResults = collect();

        if ($search !== '') {
            $searchResults = UserProfile::query()
                ->with('user')
                ->whereHas('user', function ($query) use ($search): void {
                    $query->where('verification_step', 'approved')
                        ->where(function ($nestedQuery) use ($search): void {
                            $nestedQuery
                                ->where('id', $search)
                                ->orWhere('phone_number', 'like', '%'.$search.'%');
                        });
                })
                ->whereDoesntHave('featuredProfile')
                ->latest()
                ->get();
        }

        return view('admin.featured-profiles', [
            'featuredProfiles' => $featuredProfiles,
            'search' => $search,
            'searchResults' => $searchResults,
            'featuredCount' => $featuredProfiles->count(),
            'availableSlots' => max(0, 4 - $featuredProfiles->count()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_profile_id' => ['required', 'integer'],
        ]);

        $profile = UserProfile::query()
            ->with('user')
            ->find($validated['user_profile_id']);

        if (! $profile) {
            return back()->with('error', 'The selected profile could not be found.');
        }

        if (! $profile->user || $profile->user->verification_step !== 'approved') {
            return back()->with('error', 'Only approved profiles can be featured.');
        }

        if ($profile->featuredProfile) {
            return back()->with('error', 'This profile is already featured.');
        }

        try {
            DB::transaction(function () use ($profile): void {
                FeaturedProfile::query()->lockForUpdate()->get();

                if (FeaturedProfile::query()->count() >= 4) {
                    throw new \RuntimeException('limit_reached');
                }

                FeaturedProfile::query()->create([
                    'user_profile_id' => $profile->id,
                ]);
            }, 3);
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'limit_reached') {
                return back()->with(
                    'error',
                    'You already have 4 featured profiles. Unfeature one to add another.',
                );
            }

            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to feature the selected profile. Please try again.');
        }

        return back()->with('success', 'Profile featured successfully.');
    }

    public function destroy(FeaturedProfile $featuredProfile)
    {
        $featuredProfile->delete();

        return back()->with('success', 'Profile removed from featured profiles.');
    }
}
