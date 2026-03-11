@props(['profile', 'mode' => 'normal'])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
    <!-- Image Section -->
    <div class="relative h-64 bg-gray-200 overflow-hidden">
        @if ($mode === 'redacted')
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-pink-200">
                <svg class="w-20 h-20 text-pink-300 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>

            <div class="absolute inset-0 bg-white/30 backdrop-blur-[2px]"></div>

            <!-- Lock Icon Overlay -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-700">
                <div class="bg-white/80 p-3 rounded-full backdrop-blur-sm shadow-sm mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium bg-white/80 px-3 py-1 rounded-full backdrop-blur-sm shadow-sm">
                    Pending Approval
                </span>
            </div>
        @else
            <a href="{{ route('profile.show', $profile) }}" class="block w-full h-full">
                @php $primaryUrl = $profile->primaryImageUrl(); @endphp
                @if ($primaryUrl)
                    <img src="{{ $primaryUrl }}"
                         alt="{{ $profile->full_name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-pink-200">
                        <svg class="w-20 h-20 text-pink-300 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                @endif
            </a>
        @endif

        <!-- Quick Info Badges Overlay -->
        <div class="absolute bottom-3 left-3 flex gap-2">
            <span class="px-2.5 py-1 text-xs font-medium bg-white/90 text-gray-800 rounded-md backdrop-blur-sm shadow-sm">
                {{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->age . ' yrs' : 'N/A' }}
            </span>
            <span class="px-2.5 py-1 text-xs font-medium bg-white/90 text-gray-800 rounded-md backdrop-blur-sm shadow-sm">
                {{ $profile->height_cm__Oonchi ?? 'N/A' }}
            </span>
        </div>
    </div>

    <!-- Content Section -->
    <div class="p-5">
        @if ($mode === 'redacted')
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-gray-900">
                    Hidden Profile
                </h3>
            </div>

            <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                Profile details are hidden pending account approval.
            </p>

            <div class="pt-4 border-t border-gray-100">
                <button disabled class="w-full py-2.5 px-4 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed text-center">
                    View Full Profile
                </button>
            </div>
        @else
            <div class="flex justify-between items-start mb-3">
                <a href="{{ route('profile.show', $profile) }}">
                    <h3 class="text-lg font-semibold text-gray-900 hover:text-pink-600 transition">
                        {{ $profile->full_name ?? 'N/A' }}
                    </h3>
                </a>
                <span class="inline-flex items-center text-xs font-medium text-pink-600 bg-pink-50 px-2 py-1 rounded-md">
                    {{ ucfirst($profile->marital_status ?? 'N/A') }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <!-- Location -->
                <div class="flex items-start text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="line-clamp-1">{{ $profile->mumbai_address ?? $profile->village_address ?? 'Location N/A' }}</span>
                </div>

                <!-- Education/Profession -->
                <div class="flex items-start text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="line-clamp-1">{{ $profile->occupation ?? 'Occupation N/A' }}</span>
                </div>

                <!-- Religion/Caste -->
                <div class="flex items-start text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="line-clamp-1">{{ $profile->jaath ?? 'Jaath N/A' }}</span>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('profile.show', $profile) }}" class="text-sm font-medium text-pink-600 hover:text-pink-700 flex items-center transition">
                    View Full Profile
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
