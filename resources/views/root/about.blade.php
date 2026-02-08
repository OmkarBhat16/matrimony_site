<x-layout title="About Us">
    {{-- Page Header --}}
    <section class="bg-gradient-to-br from-pink-500 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold">About Us</h1>
            <p class="mt-3 text-lg text-pink-100 max-w-2xl mx-auto">Learn more about our mission to bring people together.</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {{-- Our Mission --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed">
                At Matrimony, we believe that everyone deserves to find their perfect life partner. Our platform is built on the foundation of trust, privacy, and cultural sensitivity. We understand the importance of family values, traditions, and compatibility in building lasting relationships.
            </p>
        </div>

        {{-- What We Offer --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">What We Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Verified Profiles</h3>
                        <p class="text-sm text-gray-500 mt-1">Every profile is reviewed and approved by our admin team to ensure authenticity.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Privacy First</h3>
                        <p class="text-sm text-gray-500 mt-1">Your personal information is protected and only visible to approved members.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Advanced Filters</h3>
                        <p class="text-sm text-gray-500 mt-1">Search by religion, city, age, and more to find your ideal match.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Growing Community</h3>
                        <p class="text-sm text-gray-500 mt-1">Join thousands of members actively looking for their perfect partner.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Values --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Values</h2>
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-pink-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Trust & Safety</strong> — We verify every profile to create a safe matchmaking environment.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-pink-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Cultural Respect</strong> — We honor diverse traditions, religions, and family values.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-pink-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Transparency</strong> — No hidden fees, no fake profiles, just genuine connections.</span>
                </li>
            </ul>
        </div>
    </div>
</x-layout>