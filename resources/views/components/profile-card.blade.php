<div class="bg-white rounded-xl shadow-md overflow-hidden transition hover:shadow-lg flex flex-col">
    {{-- Profile Picture --}}
    <div class="relative h-64 bg-gray-200 overflow-hidden">
        @if ($mode === 'redacted')
            @if ($profile->profile_picture)
                <img
                    src={{$profile->profile_picture}}
                    alt="Profile"
                    class="w-full h-full object-cover blur-lg scale-110"
                />
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-pink-200">
                    <svg class="w-20 h-20 text-pink-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>
            @endif

            {{-- Overlay CTA --}}
            <a href="{{ route('register') }}" class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity">
                <svg class="w-10 h-10 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="text-white font-semibold text-sm">Register to View Profile</span>
            </a>
        @else
            @if ($profile->profile_picture)
                <a href="{{ route('profile.show', $profile) }}">
                    <img
                        src="{{ $profile->profile_picture}}"
                        alt="{{ $profile->first_name }}"
                        class="w-full h-full object-cover"
                    />
                </a>
            @else
                <a href="{{ route('profile.show', $profile) }}" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-pink-200">
                    <svg class="w-20 h-20 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </a>
            @endif
        @endif
    </div>

    {{-- Card Body --}}
    <div class="p-5 flex flex-col flex-1 justify-between">
        <div>
        @if ($mode === 'redacted')
            {{-- Redacted: first name, age only --}}
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $profile->first_name }}
                <span class="text-gray-400">••••</span>
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                {{ $profile->date_of_birth->age }} years old
            </p>

            {{-- Redacted placeholders --}}
            <div class="mt-3 space-y-2">
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="bg-gray-200 rounded h-4 w-24 inline-block"></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="bg-gray-200 rounded h-4 w-32 inline-block"></span>
                </div>
            </div>
        </div>

            <a href="{{ route('register') }}" class="mt-4 block w-full text-center bg-pink-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-pink-700 transition">
                Register to View
            </a>
        @else
            {{-- Full: name, age, city, religion, occupation --}}
            <a href="{{ route('profile.show', $profile) }}" class="block">
                <h3 class="text-lg font-semibold text-gray-900 hover:text-pink-600 transition">
                    {{ $profile->first_name }} {{ $profile->last_name }}
                </h3>
            </a>
            <p class="text-sm text-gray-500 mt-1">
                {{ $profile->date_of_birth->age }} years old
            </p>

            <div class="mt-3 space-y-2">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $profile->city }}, {{ $profile->state }}
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ $profile->religion }}
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $profile->occupation }}
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ ucfirst($profile->marital_status) }} · {{ ucfirst($profile->gender) }}
                </div>
            </div>
        </div>

            <a href="{{ route('profile.show', $profile) }}" class="mt-4 block w-full text-center border border-pink-600 text-pink-600 py-2 rounded-lg text-sm font-medium hover:bg-pink-600 hover:text-white transition">
                View Full Profile
            </a>
        @endif
    </div>
</div>
