<x-admin-layout>
    <x-slot:title>Review Profile — {{ $user->name }}</x-slot:title>
    <x-slot:header>Profile Review</x-slot:header>

    <div class="max-w-4xl mx-auto">
        <!-- User Info Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Phone: {{ $user->phone_number }}
                        @if($user->email) &middot; Email: {{ $user->email }} @endif
                    </p>
                    <span class="mt-2 inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                        {{ $user->verification_step === 'approved' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ str_replace('_', ' ', ucfirst($user->verification_step)) }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users', ['tab' => 'pending']) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        &larr; Back to List
                    </a>
                    @if($user->verification_step === 'step2_pending')
                        <form action="{{ route('users.approve', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                                Approve Profile
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if($user->profile)
            @php $profile = $user->profile; @endphp

            <!-- Profile Images -->
            @php $images = $profile->allImageUrls(); @endphp
            @if(count($images))
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Profile Photos</h3>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($images as $slot => $url)
                            <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100">
                                <img src="{{ $url }}" alt="Photo {{ $slot }}" class="w-full h-full object-cover">
                                @if($slot === ($profile->primary_image ?? 1))
                                    <span class="absolute top-2 left-2 px-2 py-0.5 text-xs font-medium bg-pink-600 text-white rounded-full">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Profile Details -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                    @foreach([
                        'Full Name' => $profile->full_name,
                        'Navras Naav' => $profile->navras_naav,
                        'Gender' => $profile->gender ? ucfirst($profile->gender) : null,
                        'Date of Birth' => $profile->date_of_birth?->format('d M Y'),
                        'Marital Status' => $profile->marital_status,
                        'Height (Oonchi)' => $profile->height_cm__Oonchi,
                        'Skin Complexion (Rang)' => $profile->skin_complexion__Rang,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Horoscope, Education &amp; Profession</h3>
                <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                    @foreach([
                        'Day & Time of Birth' => $profile->day_and_time_of_birth,
                        'Place of Birth' => $profile->place_of_birth,
                        'Zodiac Sign (Raas)' => $profile->zodiac_sign__Raas,
                        'Naadi' => $profile->naadi,
                        'Gann' => $profile->gann,
                        'Jaath' => $profile->jaath,
                        'Devak' => $profile->devak,
                        'Kul Devata' => $profile->kul_devata,
                        'Education' => $profile->education,
                        'Occupation' => $profile->occupation,
                        'Annual Income' => $profile->annual_income ? '₹' . number_format($profile->annual_income) : null,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Family Details</h3>
                <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                    @foreach([
                        "Father's Name" => $profile->fathers_name,
                        "Mother's Name" => $profile->mothers_name,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 gap-y-4 mt-4">
                    @foreach([
                        'Siblings' => $profile->siblings,
                        'Uncles' => $profile->uncles,
                        'Aunts' => $profile->aunts,
                        'Naathe Relationships' => $profile->naathe_relationships,
                        'Mumbai Address' => $profile->mumbai_address,
                        'Village Address' => $profile->village_address,
                        'Village Farm' => $profile->village_farm,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $value ?? '—' }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($user->verification_step === 'step2_pending')
                <div class="flex justify-end mb-8">
                    <form action="{{ route('users.approve', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-8 py-3 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition shadow-sm">
                            Approve Profile
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <p class="text-sm text-gray-500">This user has not created a profile yet.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
