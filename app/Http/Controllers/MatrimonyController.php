<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MatrimonyController extends Controller
{
    /**
     * Display a listing of profiles.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $showFilters = $user && $user->isApproved();

        $query = UserProfile::query()->whereHas(
            'user',
            fn ($q) => $q->where('verification_step', 'approved'),
        );

        // Exclude current user from the results
        if ($user) {
            $query->where('user_id', '!=', $user->id);
        }

        if ($showFilters) {
            if ($request->filled('gender')) {
                $query->where('gender', $request->input('gender'));
            }
            if ($request->filled('jaath')) {
                $query->where(
                    'jaath',
                    'like',
                    '%'.$request->input('jaath').'%',
                );
            }
            if ($request->filled('city')) {
                $query->where(function ($q) use ($request) {
                    $city = $request->input('city');
                    $q->where('place_of_birth', 'like', '%'.$city.'%')
                        ->orWhere('address', 'like', '%'.$city.'%')
                        ->orWhere('native_address', 'like', '%'.$city.'%');
                });
            }

            if ($request->filled('age_min')) {
                $query->whereDate(
                    'date_of_birth',
                    '<=',
                    Carbon::now()
                        ->subYears($request->input('age_min'))
                        ->format('Y-m-d'),
                );
            }
            if ($request->filled('age_max')) {
                // If they specify max age 30, they can be up to 30.99 years old
                $query->whereDate(
                    'date_of_birth',
                    '>=',
                    Carbon::now()
                        ->subYears($request->input('age_max') + 1)
                        ->format('Y-m-d'),
                );
            }
        }

        $profiles = $query->latest()->paginate(12)->withQueryString();

        $cities = UserProfile::query()
            ->whereHas('user', fn ($q) => $q->where('verification_step', 'approved'))
            ->whereNotNull('place_of_birth')
            ->distinct()
            ->orderBy('place_of_birth')
            ->pluck('place_of_birth');

        $jaaths = UserProfile::query()
            ->whereHas('user', fn ($q) => $q->where('verification_step', 'approved'))
            ->whereNotNull('jaath')
            ->distinct()
            ->orderBy('jaath')
            ->pluck('jaath');

        return view('root.matrimony', [
            'profiles' => $profiles,
            'cities' => $cities,
            'jaaths' => $jaaths,
        ]);
    }
}
