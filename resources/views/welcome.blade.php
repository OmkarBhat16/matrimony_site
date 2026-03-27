<x-layout title="Find Your Perfect Match">
    @php
        $home = $homePageContent ?? \App\Models\HomePageContent::defaults();
        $featuredProfiles = $featuredProfiles ?? \App\Models\FeaturedProfile::query()
            ->with(['userProfile.user'])
            ->latest()
            ->limit(4)
            ->get()
            ->pluck('userProfile')
            ->filter(fn ($profile) => $profile?->user?->verification_step === 'approved')
            ->values();
    @endphp

    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-pink-500 via-pink-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="max-w-2xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight">
                    {{ data_get($home, 'hero.title') }}
                    <span class="text-pink-200">{{ data_get($home, 'hero.highlight') }}</span>
                </h1>
                <p class="mt-4 text-lg text-pink-100 leading-relaxed">
                    {{ data_get($home, 'hero.description') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-pink-600 font-semibold rounded-lg hover:bg-pink-50 transition shadow-lg">
                        {{ data_get($home, 'hero.register_button') }}
                    </a>
                    <a href="{{ route('root.matrimony') }}" class="px-8 py-3 border-2 border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 transition">
                        {{ data_get($home, 'hero.browse_button') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    {{-- Stats Section --}}
    <section class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                @foreach(data_get($home, 'stats', []) as $stat)
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <p class="text-3xl font-bold text-pink-600">{{ $stat['value'] ?? '' }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Profiles --}}
    @if ($featuredProfiles->count())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">{{ data_get($home, 'featured.title') }}</h2>
                    <p class="mt-2 text-gray-500">{{ data_get($home, 'featured.subtitle') }}</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProfiles as $profile)
                        <x-profile-card :profile="$profile"/>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- How It Works --}}
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">{{ data_get($home, 'how_it_works.title') }}</h2>
                <p class="mt-2 text-gray-500">{{ data_get($home, 'how_it_works.subtitle') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach(data_get($home, 'how_it_works.steps', []) as $step)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $step['title'] ?? '' }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ $step['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-2xl p-10 lg:p-14 shadow-xl">
                <h2 class="text-2xl lg:text-3xl font-bold text-white">{{ data_get($home, 'cta.title') }}</h2>
                <p class="mt-3 text-pink-100">{{ data_get($home, 'cta.description') }}</p>
                <a href="{{ route('register') }}" class="mt-6 inline-block bg-white text-pink-600 px-8 py-3 rounded-lg font-semibold hover:bg-pink-50 transition shadow-lg">
                    {{ data_get($home, 'cta.button') }}
                </a>
            </div>
        </div>
    </section>
</x-layout>
