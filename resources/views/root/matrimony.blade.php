<x-layout title="Matrimony">
    @php
        $showFilters = auth()->check() && auth()->user()->isApproved();
    @endphp
    {{-- Pending Approval Banner --}}
    @auth
        @unless (auth()->user()->isApproved())
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4">
                <div class="max-w-7xl mx-auto flex items-center gap-3">
                    <svg class="w-6 h-6 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-sm text-amber-700 font-medium">
                        Your account is pending approval. An administrator will review your account shortly. You'll be able to browse profiles once approved.
                    </p>
                </div>
            </div>
        @endunless
    @endauth

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Page Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Find Your Perfect Match</h1>
            <p class="mt-2 text-gray-500">Browse profiles of verified members in our community.</p>
        </div>

        {{-- Filters (only for approved users with profiles) --}}
        @if ($showFilters)
            <form method="GET" action="{{ route('root.matrimony') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-900">Filter Profiles</h2>
                    </div>
                    @if (request()->hasAny(['gender', 'jaath', 'city', 'age_min', 'age_max']))
                        <a href="{{ route('root.matrimony') }}" class="text-xs text-pink-600 hover:text-pink-700 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear all
                        </a>
                    @endif
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        {{-- Gender Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ request('gender', '') }}' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Gender</label>
                            <input type="hidden" name="gender" :value="selected">
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected ? (selected === 'male' ? 'Male' : (selected === 'female' ? 'Female' : 'Other')) : 'Any gender'"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <button type="button" @click="selected = ''; open = false" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any gender</button>
                                <button type="button" @click="selected = 'male'; open = false" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === 'male' && 'text-pink-600 font-medium'">Male</button>
                                <button type="button" @click="selected = 'female'; open = false" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === 'female' && 'text-pink-600 font-medium'">Female</button>
                            </div>
                        </div>

                        {{-- Religion / Jaath Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ request('jaath', '') }}', search: '' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Religion / Jaath</label>
                            <input type="hidden" name="jaath" :value="selected">
                            <button type="button" @click="open = !open; $nextTick(() => $refs.jaathSearch?.focus())" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any jaath'" class="truncate"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <div class="px-3 py-1.5">
                                    <input x-ref="jaathSearch" x-model="search" type="text" placeholder="Search..." class="w-full px-2.5 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-pink-400">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any jaath</button>
                                    @foreach ($jaaths as $jaath)
                                        <button type="button" x-show="!search || '{{ strtolower($jaath) }}'.includes(search.toLowerCase())" @click="selected = '{{ addslashes($jaath) }}'; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ addslashes($jaath) }}' && 'text-pink-600 font-medium'">{{ ucfirst($jaath) }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- City Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ request('city', '') }}', search: '' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">City</label>
                            <input type="hidden" name="city" :value="selected">
                            <button type="button" @click="open = !open; $nextTick(() => $refs.citySearch?.focus())" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any city'" class="truncate"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <div class="px-3 py-1.5">
                                    <input x-ref="citySearch" x-model="search" type="text" placeholder="Search..." class="w-full px-2.5 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-pink-400">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any city</button>
                                    @foreach ($cities as $city)
                                        <button type="button" x-show="!search || '{{ strtolower($city) }}'.includes(search.toLowerCase())" @click="selected = '{{ addslashes($city) }}'; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ addslashes($city) }}' && 'text-pink-600 font-medium'">{{ $city }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Age Range --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Age Range</label>
                            <div class="flex gap-2">
                                <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min" min="18" max="100" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition placeholder:text-gray-400">
                                <span class="self-center text-gray-300">&ndash;</span>
                                <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max" min="18" max="100" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition placeholder:text-gray-400">
                            </div>
                        </div>

                        {{-- Apply Button --}}
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-pink-600 text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-pink-700 active:bg-pink-800 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        {{-- Profile Grid --}}
        @if ($profiles->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($profiles as $profile)
                    <x-profile-card
                        :profile="$profile"
                        :mode="$showFilters ? 'full' : 'redacted'"
                    />
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($showFilters)
                <div class="mt-8">
                    {{ $profiles->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <svg class="mx-auto w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No profiles found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or check back later.</p>
            </div>
        @endif

        {{-- Guest CTA --}}
        @guest
            <div class="mt-12 text-center bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl p-10">
                <h2 class="text-2xl font-bold text-gray-900">Ready to Find Your Match?</h2>
                <p class="mt-2 text-gray-600">Join thousands of verified members and start your journey today.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-block bg-pink-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-pink-700 transition">
                    Register Now
                </a>
            </div>
        @endguest
    </div>
</x-layout>
