<x-layout>
    <x-slot:title>Pending Review - Matrimony</x-slot:title>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <!-- Clock Icon -->
                <div class="mx-auto w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Profile Under Review</h2>
                <p class="text-gray-600 mb-6">
                    Your profile has been submitted and is currently being reviewed by an administrator.
                    You'll be able to access the full site once your profile is approved.
                </p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
