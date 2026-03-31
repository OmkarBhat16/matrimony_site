<x-layout>
    <x-slot:title>Registration Submitted - Matrimony</x-slot:title>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <!-- Success Icon -->
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Registration Submitted!</h2>
                <p class="text-gray-600 mb-6">
                    Your registration has been received. An admin will review your details and activate your account.
                    Once activated, you can sign in with the password you created.
                </p>

                <a href="/" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</x-layout>
