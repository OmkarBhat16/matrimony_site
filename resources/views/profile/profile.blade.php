<x-layout title="My Profile">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h1>

        @if (auth()->user()->profile)
            @php $profile = auth()->user()->profile; @endphp

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="relative">
                    <div class="h-32 bg-gradient-to-r from-pink-500 to-purple-600"></div>
                    <div class="absolute -bottom-12 left-8">
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-200">
                            @if ($profile->profile_picture)
                                <img src="{{ $profile->profile_picture }}" alt="{{ $profile->first_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-pink-100">
                                    <svg class="w-12 h-12 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-16 px-8 pb-8">
                    <h2 class="text-xl font-bold text-gray-900">{{ $profile->first_name }} {{ $profile->last_name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ $profile->date_of_birth->age }} years old &middot; {{ ucfirst($profile->gender) }} &middot; {{ ucfirst($profile->marital_status) }}
                    </p>

                    @if ($profile->bio)
                        <p class="mt-4 text-gray-600 text-sm leading-relaxed">{{ $profile->bio }}</p>
                    @endif

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div>
                            <h3 class="text-xs font-semibold text-pink-600 uppercase tracking-wider mb-3">Personal</h3>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between"><dt class="text-gray-500">Religion</dt><dd class="font-medium text-gray-900">{{ $profile->religion }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Caste</dt><dd class="font-medium text-gray-900">{{ $profile->caste }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Mother Tongue</dt><dd class="font-medium text-gray-900">{{ $profile->mother_tongue }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Education</dt><dd class="font-medium text-gray-900">{{ $profile->education }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Occupation</dt><dd class="font-medium text-gray-900">{{ $profile->occupation }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-pink-600 uppercase tracking-wider mb-3">Location & Contact</h3>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between"><dt class="text-gray-500">City</dt><dd class="font-medium text-gray-900">{{ $profile->city }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">State</dt><dd class="font-medium text-gray-900">{{ $profile->state }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-900">{{ $profile->phone_number }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900">{{ auth()->user()->email }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm p-10 text-center">
                <svg class="mx-auto w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No Profile Yet</h3>
                <p class="mt-1 text-sm text-gray-500">Create your profile to start connecting with other members.</p>
                @if (auth()->user()->approved)
                    <a href="{{ route('onboarding.create') }}" class="mt-6 inline-block bg-pink-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-pink-700 transition">
                        Create Profile
                    </a>
                @else
                    <p class="mt-4 text-sm text-amber-600 font-medium">Your account is pending approval. You can create your profile once approved.</p>
                @endif
            </div>
        @endif
    </div>
</x-layout>