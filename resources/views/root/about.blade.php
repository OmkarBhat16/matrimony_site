<x-layout title="About Us">
    @php
        $about = $aboutPageContent ?? \App\Models\AboutPageContent::defaults();
    @endphp

    {{-- Page Header --}}
    <section class="bg-gradient-to-br from-pink-500 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold">{{ data_get($about, 'header.title') }}</h1>
            <p class="mt-3 text-lg text-pink-100 max-w-2xl mx-auto">{{ data_get($about, 'header.subtitle') }}</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {{-- Our Mission --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ data_get($about, 'mission.title') }}</h2>
            <p class="text-gray-600 leading-relaxed">
                {{ data_get($about, 'mission.description') }}
            </p>
        </div>

        {{-- What We Offer --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">What We Offer</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(data_get($about, 'offers', []) as $offer)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $offer['title'] ?? '' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $offer['description'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Values --}}
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ data_get($about, 'values.title') }}</h2>
            <ul class="space-y-3 text-gray-600">
                @foreach(data_get($about, 'values.items', []) as $valueItem)
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-pink-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $valueItem }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-layout>
