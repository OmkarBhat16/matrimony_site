<x-layout>
    <x-slot:title>Register - Matrimony</x-slot:title>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="lang-label text-3xl font-extrabold text-gray-900" data-en="Register Your Interest" data-mr="आपली रुची नोंदवा">
                    Register Your Interest
                </h2>
                <p class="lang-label mt-2 text-sm text-gray-600" data-en="Submit your details and an admin will create your account" data-mr="तुमची माहिती सबमिट करा आणि प्रशासक तुमचे खाते तयार करतील">
                    Submit your details and an admin will create your account
                </p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" class="space-y-5">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="lang-label" data-en="Full Name" data-mr="पूर्ण नाव">Full Name</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                required
                                value="{{ old('name') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('name') border-red-500 @enderror"
                                placeholder="John Doe"
                                data-placeholder-en="John Doe"
                                data-placeholder-mr="उदा. राहुल पाटील"
                            >
                        </div>
                    </div>

                    <!-- Phone Number Field -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="lang-label" data-en="Phone Number" data-mr="मोबाईल नंबर">Phone Number</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                                +91
                            </span>
                            <input
                                id="phone_number"
                                name="phone_number"
                                type="tel"
                                autocomplete="tel"
                                required
                                inputmode="numeric"
                                maxlength="10"
                                minlength="10"
                                pattern="[0-9]{10}"
                                value="{{ old('phone_number') }}"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('phone_number') border-red-500 @enderror"
                                placeholder="98765 43210"
                                data-placeholder-en="98765 43210"
                                data-placeholder-mr="98765 43210"
                            >
                        </div>
                    </div>

                    <!-- Email Field (Optional) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="lang-label" data-en="Email Address" data-mr="ईमेल पत्ता">Email Address</span> <span class="lang-label text-gray-400 text-xs" data-en="(optional)" data-mr="(ऐच्छिक)">(optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('email') border-red-500 @enderror"
                                placeholder="you@example.com"
                                data-placeholder-en="you@example.com"
                                data-placeholder-mr="you@example.com"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition transform hover:scale-[1.02]"
                        >
                            <span class="lang-label" data-en="Register" data-mr="नोंदणी करा">Register</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600">
                <span class="lang-label" data-en="Already have an account?" data-mr="आधीच खाते आहे का?">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500 transition">
                    <span class="lang-label" data-en="Sign in" data-mr="लॉगिन करा">Sign in</span>
                </a>
            </p>
        </div>
    </div>
</x-layout>
