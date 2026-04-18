<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MatrimonyController extends Controller
{
    private const FILTER_GENDERS = ['male', 'female'];

    private const FILTER_BLOOD_GROUPS = [
        'A +ve',
        'A -ve',
        'B +ve',
        'B -ve',
        'AB +ve',
        'AB -ve',
        'O +ve',
        'O -ve',
    ];

    private const FILTER_BLOOD_GROUP_VARIANTS = [
        'A +ve' => ['A +ve', 'A+ve', 'A+', 'A positive'],
        'A -ve' => ['A -ve', 'A-ve', 'A-', 'A negative'],
        'B +ve' => ['B +ve', 'B+ve', 'B+', 'B positive'],
        'B -ve' => ['B -ve', 'B-ve', 'B-', 'B negative'],
        'AB +ve' => ['AB +ve', 'AB+ve', 'AB+', 'AB positive'],
        'AB -ve' => ['AB -ve', 'AB-ve', 'AB-', 'AB negative'],
        'O +ve' => ['O +ve', 'O+ve', 'O+', 'O positive'],
        'O -ve' => ['O -ve', 'O-ve', 'O-', 'O negative', 'O -Ve'],
    ];

    private const FILTER_RAAS = [
        'Mesh',
        'Vrishabh',
        'Mithun',
        'Kark',
        'Singh',
        'Kanya',
        'Tula',
        'Vrishchik',
        'Dhanu',
        'Makar',
        'Kumbh',
        'Meen',
    ];

    private const FILTER_GANN = [
        'Dev',
        'Manusha',
        'Rakshas',
    ];

    /**
     * Display a listing of profiles.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $showFilters = $user && $user->isApproved();

        $query = UserProfile::query()->with('user')->whereHas(
            'user',
            fn ($q) => $q->where('verification_step', 'approved'),
        );

        // Exclude current user from the results
        if ($user) {
            $query->where('user_id', '!=', $user->id);
        }

        $currentYear = (int) Carbon::now()->format('Y');
        $validated = [];

        if ($showFilters) {
            $validator = Validator::make($request->query(), [
                'gender' => ['nullable', Rule::in(self::FILTER_GENDERS)],
                'blood_group' => ['nullable', Rule::in(self::FILTER_BLOOD_GROUPS)],
                'education_type' => ['nullable', Rule::in(UserProfile::EDUCATION_TYPES)],
                'zodiac_sign__Raas' => ['nullable', Rule::in(self::FILTER_RAAS)],
                'gann' => ['nullable', Rule::in(self::FILTER_GANN)],
                'year_from' => ['nullable', 'integer', 'digits:4', 'min:1950', 'max:'.$currentYear],
                'year_to' => ['nullable', 'integer', 'digits:4', 'min:1950', 'max:'.$currentYear],
            ]);

            $validator->after(function ($validator) use ($request): void {
                $yearFrom = $request->query('year_from');
                $yearTo = $request->query('year_to');

                if ($yearFrom !== null && $yearTo !== null && $yearFrom !== '' && $yearTo !== '' && (int) $yearFrom > (int) $yearTo) {
                    $validator->errors()->add('year_from', 'Year From must be less than or equal to Year To.');
                }
            });

            $validated = $validator->validate();

            if (! empty($validated['gender'])) {
                $query->where('gender', $validated['gender']);
            }

            if (! empty($validated['blood_group'])) {
                $query->whereIn('blood_group', self::FILTER_BLOOD_GROUP_VARIANTS[$validated['blood_group']] ?? [$validated['blood_group']]);
            }

            if (! empty($validated['education_type'])) {
                $query->where('education_type', $validated['education_type']);
            }

            if (! empty($validated['zodiac_sign__Raas'])) {
                $query->whereRaw('LOWER(zodiac_sign__Raas) = ?', [strtolower($validated['zodiac_sign__Raas'])]);
            }

            if (! empty($validated['gann'])) {
                $query->whereRaw('LOWER(gann) = ?', [strtolower($validated['gann'])]);
            }

            if (! empty($validated['year_from'])) {
                $query->whereYear('date_of_birth', '>=', (int) $validated['year_from']);
            }

            if (! empty($validated['year_to'])) {
                $query->whereYear('date_of_birth', '<=', (int) $validated['year_to']);
            }
        }

        $profiles = $query->latest()->paginate(12)->withQueryString();

        return view('root.matrimony', [
            'profiles' => $profiles,
            'filterOptions' => [
                'genders' => self::FILTER_GENDERS,
                'blood_groups' => self::FILTER_BLOOD_GROUPS,
                'education_types' => UserProfile::EDUCATION_TYPES,
                'raas' => self::FILTER_RAAS,
                'gann' => self::FILTER_GANN,
                'year_min' => 1950,
                'year_max' => $currentYear,
            ],
            'filters' => $validated,
        ]);
    }
}
