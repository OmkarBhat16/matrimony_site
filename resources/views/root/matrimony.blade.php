<x-layout title="Matrimony">
    @php
        $showFilters = auth()->check() && auth()->user()->isApproved();
        $filters = $filters ?? [];
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
            <form method="GET" action="{{ route('root.matrimony') }}" x-data="{ filtersCollapsed: false }" class="bg-white/95 backdrop-blur rounded-2xl shadow-lg border border-gray-100 mb-8 md:sticky md:top-20 z-30">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-900">Filter Profiles</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        @if (request()->hasAny(['gender', 'blood_group', 'education_type', 'zodiac_sign__Raas', 'gann', 'year_from', 'year_to']))
                            <a href="{{ route('root.matrimony') }}" class="text-xs text-pink-600 hover:text-pink-700 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear all
                            </a>
                        @endif

                        <button type="button" @click="filtersCollapsed = !filtersCollapsed" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:text-pink-600 hover:border-pink-300 transition" :aria-expanded="(!filtersCollapsed).toString()" aria-label="Toggle filters">
                            <svg class="w-4 h-4 transition-transform duration-200" :class="filtersCollapsed ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6" x-show="!filtersCollapsed" x-transition.opacity.duration.200ms>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Gender Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ $filters['gender'] ?? '' }}' }" class="relative">
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

                        {{-- Blood Group Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ $filters['blood_group'] ?? '' }}', search: '' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Blood Group</label>
                            <input type="hidden" name="blood_group" :value="selected">
                            <button type="button" @click="open = !open; $nextTick(() => $refs.bloodSearch?.focus())" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any blood group'" class="truncate"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <div class="px-3 py-1.5">
                                    <input x-ref="bloodSearch" x-model="search" type="text" placeholder="Search..." class="w-full px-2.5 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-pink-400">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any blood group</button>
                                    @foreach ($filterOptions['blood_groups'] as $bloodGroup)
                                        <button type="button" x-show="!search || '{{ strtolower($bloodGroup) }}'.includes(search.toLowerCase())" @click="selected = '{{ addslashes($bloodGroup) }}'; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ addslashes($bloodGroup) }}' && 'text-pink-600 font-medium'">{{ $bloodGroup }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Education Type Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ $filters['education_type'] ?? '' }}', search: '' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Education Type</label>
                            <input type="hidden" name="education_type" :value="selected">
                            <button type="button" @click="open = !open; $nextTick(() => $refs.educationSearch?.focus())" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any education type'" class="truncate"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <div class="px-3 py-1.5">
                                    <input x-ref="educationSearch" x-model="search" type="text" placeholder="Search..." class="w-full px-2.5 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-pink-400">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any education type</button>
                                    @foreach ($filterOptions['education_types'] as $educationType)
                                        <button type="button" x-show="!search || '{{ strtolower($educationType) }}'.includes(search.toLowerCase())" @click="selected = '{{ addslashes($educationType) }}'; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ addslashes($educationType) }}' && 'text-pink-600 font-medium'">{{ $educationType }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Raas Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ $filters['zodiac_sign__Raas'] ?? '' }}', search: '' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Raas</label>
                            <input type="hidden" name="zodiac_sign__Raas" :value="selected">
                            <button type="button" @click="open = !open; $nextTick(() => $refs.raasSearch?.focus())" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any raas'" class="truncate"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <div class="px-3 py-1.5">
                                    <input x-ref="raasSearch" x-model="search" type="text" placeholder="Search..." class="w-full px-2.5 py-1.5 text-sm border border-gray-200 rounded-md focus:outline-none focus:border-pink-400">
                                </div>
                                <div class="max-h-40 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any raas</button>
                                    @foreach ($filterOptions['raas'] as $raas)
                                        <button type="button" x-show="!search || '{{ strtolower($raas) }}'.includes(search.toLowerCase())" @click="selected = '{{ addslashes($raas) }}'; open = false; search = ''" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ addslashes($raas) }}' && 'text-pink-600 font-medium'">{{ $raas }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Gann Dropdown --}}
                        <div x-data="{ open: false, selected: '{{ $filters['gann'] ?? '' }}' }" class="relative">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Gann</label>
                            <input type="hidden" name="gann" :value="selected">
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition">
                                <span :class="selected ? 'text-gray-900' : 'text-gray-400'" x-text="selected || 'Any gann'"></span>
                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition.opacity.duration.150ms class="absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-lg shadow-lg py-1">
                                <button type="button" @click="selected = ''; open = false" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="!selected && 'text-pink-600 font-medium'">Any gann</button>
                                @foreach ($filterOptions['gann'] as $gann)
                                    <button type="button" @click="selected = '{{ $gann }}'; open = false" class="w-full px-3.5 py-2 text-sm text-left hover:bg-pink-50 transition" :class="selected === '{{ $gann }}' && 'text-pink-600 font-medium'">{{ $gann }}</button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Year Range --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Year Range</label>
                            <div class="flex gap-2">
                                <input type="number" name="year_from" value="{{ $filters['year_from'] ?? '' }}" placeholder="From" min="{{ $filterOptions['year_min'] }}" max="{{ $filterOptions['year_max'] }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition placeholder:text-gray-400">
                                <span class="self-center text-gray-300">&ndash;</span>
                                <input type="number" name="year_to" value="{{ $filters['year_to'] ?? '' }}" placeholder="To" min="{{ $filterOptions['year_min'] }}" max="{{ $filterOptions['year_max'] }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-400 transition placeholder:text-gray-400">
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
