<x-layout title="Find Your Perfect Match">
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-pink-500 via-pink-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="max-w-2xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight">
                    Find Your <span class="text-pink-200">Perfect Match</span>
                </h1>
                <p class="mt-4 text-lg text-pink-100 leading-relaxed">
                    Join our trusted community of verified profiles. We help you connect with like-minded individuals who share your values, culture, and interests.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-pink-600 font-semibold rounded-lg hover:bg-pink-50 transition shadow-lg">
                        Get Started Free
                    </a>
                    <a href="{{ route('root.matrimony') }}" class="px-8 py-3 border-2 border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 transition">
                        Browse Profiles
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
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <p class="text-3xl font-bold text-pink-600">1000+</p>
                    <p class="text-sm text-gray-500 mt-1">Verified Profiles</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <p class="text-3xl font-bold text-pink-600">500+</p>
                    <p class="text-sm text-gray-500 mt-1">Successful Matches</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <p class="text-3xl font-bold text-pink-600">100%</p>
                    <p class="text-sm text-gray-500 mt-1">Privacy Focused</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <p class="text-3xl font-bold text-pink-600">24/7</p>
                    <p class="text-sm text-gray-500 mt-1">Support Available</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Profiles --}}
    @if ($featuredProfiles->count())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Featured Profiles</h2>
                    <p class="mt-2 text-gray-500">Meet some of our verified community members</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProfiles as $profile)
                        <x-profile-card :profile="$profile" mode="redacted" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- How It Works --}}
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
                <p class="mt-2 text-gray-500">Finding your partner is simple with us</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">1. Create Profile</h3>
                    <p class="mt-2 text-sm text-gray-500">Register and build your detailed profile with your preferences and background.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">2. Browse & Filter</h3>
                    <p class="mt-2 text-sm text-gray-500">Search verified profiles by religion/jaath, city, age, and more to find compatible matches.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">3. Connect</h3>
                    <p class="mt-2 text-sm text-gray-500">View full profiles, download details, and take the next step towards your future together.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-2xl p-10 lg:p-14 shadow-xl">
                <h2 class="text-2xl lg:text-3xl font-bold text-white">Ready to Begin Your Journey?</h2>
                <p class="mt-3 text-pink-100">Join our growing community and find the partner you've been looking for.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-block bg-white text-pink-600 px-8 py-3 rounded-lg font-semibold hover:bg-pink-50 transition shadow-lg">
                    Register Now — It's Free
                </a>
            </div>
        </div>
    </section>
</x-layout>
