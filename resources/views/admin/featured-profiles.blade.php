<x-admin-layout>
    <x-slot:title>Featured Profiles</x-slot:title>
    <x-slot:header>Featured Profiles</x-slot:header>

    @php
        $currentCount = $featuredCount ?? 0;
        $slotsLeft = $availableSlots ?? max(0, 4 - $currentCount);
        $searchTerm = $search ?? '';
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Featured Now</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $currentCount }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Slots Left</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $slotsLeft }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Limit</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">4</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Current Featured Profiles</h2>
                    <p class="text-sm text-gray-500 mt-1">These are the profiles currently highlighted on the site.</p>
                </div>
            </div>

            @if ($featuredProfiles->count())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($featuredProfiles as $featuredProfile)
                        @php
                            $profile = $featuredProfile->userProfile;
                            $user = $profile?->user;
                        @endphp
                        @if ($profile && $user)
                            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900">{{ $profile->full_name ?? $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->phone_number ?? 'No phone number' }}</p>
                                        <p class="text-sm text-gray-500">User ID: {{ $user->public_id ?? $user->id }}</p>
                                        <p class="mt-2 inline-flex items-center rounded-full bg-pink-50 px-2.5 py-1 text-xs font-medium text-pink-700">
                                            Featured
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.featured-profiles.destroy', $featuredProfile) }}" onsubmit="return confirm('Remove this profile from featured profiles?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition">
                                            Unfeature
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-4 text-sm text-gray-600 space-y-1">
                                    <p><span class="font-medium text-gray-900">Email:</span> {{ $user->email ?? 'N/A' }}</p>
                                    <p><span class="font-medium text-gray-900">Profile ID:</span> {{ $profile->id }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <p class="text-gray-500">No profiles are featured yet.</p>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900">Add Featured Profile</h2>
                <p class="text-sm text-gray-500 mt-1">Search by phone number or user ID, then feature the profile from the results.</p>

                @if ($slotsLeft <= 0)
                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        You already have 4 featured profiles. Unfeature one to add another.
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.featured-profiles') }}" class="mt-5 space-y-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input
                            id="search"
                            name="search"
                            type="text"
                            value="{{ $searchTerm }}"
                            placeholder="Enter phone number or user ID"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2"
                        >
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800 transition">
                        Search Profiles
                    </button>
                </form>

                @if ($searchTerm !== '')
                    <div class="mt-5 space-y-3">
                        <p class="text-sm font-medium text-gray-700">Search results</p>

                        @if ($searchResults->count())
                            <div class="space-y-3">
                                @foreach ($searchResults as $profile)
                                    <div class="rounded-xl border border-gray-200 p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $profile->full_name ?? $profile->user?->name }}</p>
                                                <p class="text-sm text-gray-500">User ID: {{ $profile->user?->public_id ?? $profile->user?->id ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500">Phone: {{ $profile->user?->phone_number ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500">Profile ID: {{ $profile->id }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('admin.featured-profiles.store') }}">
                                                @csrf
                                                <input type="hidden" name="user_profile_id" value="{{ $profile->id }}">
                                                <button type="submit" class="rounded-lg bg-pink-600 px-3 py-2 text-sm font-medium text-white hover:bg-pink-700 transition disabled:cursor-not-allowed disabled:bg-pink-300" {{ $slotsLeft <= 0 ? 'disabled' : '' }}>
                                                    Add
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                                No approved, non-featured profiles matched that search.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-5 rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                        Search by phone number or user ID to find a profile to feature.
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900">At a glance</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li>• Maximum of 4 featured profiles at any time.</li>
                    <li>• Featured profiles show on the home page.</li>
                    <li>• Unfeature any profile to free a slot instantly.</li>
                </ul>
            </div>
        </div>
    </div>
</x-admin-layout>
