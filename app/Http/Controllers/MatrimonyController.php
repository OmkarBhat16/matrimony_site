<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatrimonyController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        // Approved user with no profile → redirect to onboarding
        if ($user && $user->approved && !$user->profile()->exists()) {
            return redirect()->route('onboarding.create');
        }

        // Build query for approved users' profiles (exclude current user)
        $query = UserProfile::query()
            ->whereHas('user', fn ($q) => $q->where('approved', true))
            ->with('user');

        if ($user) {
            $query->where('user_id', '!=', $user->id);
        }

        // Apply filters for authenticated approved users with profiles
        $showFilters = $user && $user->approved && $user->profile()->exists();

        if ($showFilters) {
            if ($request->filled('gender')) {
                $query->where('gender', $request->input('gender'));
            }
            if ($request->filled('religion')) {
                $query->where('religion', 'like', '%' . $request->input('religion') . '%');
            }
            if ($request->filled('city')) {
                $query->where('city', 'like', '%' . $request->input('city') . '%');
            }
            if ($request->filled('age_min')) {
                $query->whereDate('date_of_birth', '<=', now()->subYears((int) $request->input('age_min')));
            }
            if ($request->filled('age_max')) {
                $query->whereDate('date_of_birth', '>=', now()->subYears((int) $request->input('age_max')));
            }
        }

        $profiles = $query->latest()->paginate(12)->withQueryString();

        if(!auth()->check()) {
            $profiles = $profiles->take(6); // Show only 6 profiles to guests
        }

        $religions = [];
        $cities = [];

        if ($showFilters) {
            $religions = UserProfile::query()
                ->whereHas('user', fn ($q) => $q->where('approved', true))
                ->whereNotNull('religion')
                ->distinct()
                ->orderBy('religion')
                ->pluck('religion')
                ->all();

            $cities = UserProfile::query()
                ->whereHas('user', fn ($q) => $q->where('approved', true))
                ->whereNotNull('city')
                ->distinct()
                ->orderBy('city')
                ->pluck('city')
                ->all();
        }

        return view('root.matrimony', [
            'profiles' => $profiles,
            'showFilters' => $showFilters,
            'religions' => $religions,
            'cities' => $cities,
        ]);
    }
}
