<x-layout>
    <x-slot:title>Login - Matrimony</x-slot:title>

    @php
        $supportName = config('support.admin_name');
        $supportEmail = config('support.email');
        $supportPhone = config('support.phone');
        $supportWhatsapp = config('support.whatsapp');
        $supportHours = config('support.hours');
        $whatsappLink = 'https://wa.me/' . preg_replace('/\D+/', '', (string) $supportWhatsapp);
    @endphp

    <div
        class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50"
        x-data="{ forgotPasswordOpen: false }"
        @keydown.escape.window="forgotPasswordOpen = false"
    >
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Welcome Back
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sign in to your account to continue your journey
                </p>
            </div>

            <!-- Login Card -->
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

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-600">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Phone Number Field -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
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
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition @error('password') border-red-500 @enderror"
                                placeholder="••••••••"
                            >
                        </div>
                        <div class="mt-2 flex justify-end">
                            <button
                                type="button"
                                @click="forgotPasswordOpen = true"
                                class="text-sm font-medium text-pink-600 hover:text-pink-500 transition"
                            >
                                Forgot Password?
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition transform hover:scale-[1.02]"
                        >
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-pink-600 hover:text-pink-500 transition">
                    Register now
                </a>
            </p>
        </div>

        <!-- Forgot Password Modal -->
        <div
            x-cloak
            x-show="forgotPasswordOpen"
            x-transition.opacity.duration.200ms
            class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
            aria-labelledby="forgot-password-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="absolute inset-0 bg-gray-950/60 backdrop-blur-sm"
                @click="forgotPasswordOpen = false"
            ></div>

            <div
                x-transition.scale.origin.center.duration.200ms
                class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5"
            >
                <div class="bg-gradient-to-r from-pink-600 to-purple-600 px-6 py-5 text-white">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-pink-100">
                        Need help signing in?
                    </p>
                    <h3 id="forgot-password-title" class="mt-2 text-2xl font-bold">
                        Forgot Password
                    </h3>
                    <p class="mt-2 text-sm text-pink-50">
                        Contact {{ $supportName }} to reset your password securely.
                    </p>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <div class="rounded-2xl border border-pink-100 bg-pink-50/80 p-4">
                        <p class="text-sm font-semibold text-gray-900">
                            Contact Details
                        </p>
                        <dl class="mt-3 space-y-3 text-sm">
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500">Admin</dt>
                                <dd class="text-right font-medium text-gray-900">{{ $supportName }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500">Email</dt>
                                <dd class="text-right">
                                    <a href="mailto:{{ $supportEmail }}" class="font-medium text-pink-600 hover:text-pink-500">
                                        {{ $supportEmail }}
                                    </a>
                                </dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500">Phone</dt>
                                <dd class="text-right">
                                    <a href="tel:{{ preg_replace('/\s+/', '', (string) $supportPhone) }}" class="font-medium text-pink-600 hover:text-pink-500">
                                        {{ $supportPhone }}
                                    </a>
                                </dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500">WhatsApp</dt>
                                <dd class="text-right">
                                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" class="font-medium text-pink-600 hover:text-pink-500">
                                        {{ $supportWhatsapp }}
                                    </a>
                                </dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-gray-500">Working Hours</dt>
                                <dd class="text-right font-medium text-gray-900">{{ $supportHours }}</dd>
                            </div>
                        </dl>
                    </div>

                    <p class="text-sm text-gray-600">
                        Share your registered phone number when you contact admin, and the team will help you verify and reset access.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="mailto:{{ $supportEmail }}"
                            class="inline-flex justify-center items-center rounded-lg bg-pink-600 px-4 py-3 text-sm font-medium text-white hover:bg-pink-700 transition"
                        >
                            Email Admin
                        </a>
                        <a
                            href="{{ $whatsappLink }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex justify-center items-center rounded-lg border border-pink-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-pink-50 transition"
                        >
                            WhatsApp Admin
                        </a>
                    </div>

                    <button
                        type="button"
                        @click="forgotPasswordOpen = false"
                        class="w-full rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
