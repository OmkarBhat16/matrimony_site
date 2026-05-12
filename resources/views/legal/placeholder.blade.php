<x-layout>
    <x-slot:title>{{ $pageName }}</x-slot:title>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50">
        <div class="max-w-xl w-full">
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 text-center">
                <div class="mx-auto w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10M7 16h6M9 3h6l6 6v12a2 2 0 01-2 2H9a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900">{{ $pageName }} Coming soon</h1>
                <p class="mt-4 text-sm leading-6 text-gray-600">
                    The full {{ $pageName }} content will be added here once provided by the client.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Back to Home
                    </a>
                    @if (request()->routeIs('legal.terms'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg bg-pink-600 px-5 py-3 text-sm font-medium text-white hover:bg-pink-700 transition">
                            Register
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
