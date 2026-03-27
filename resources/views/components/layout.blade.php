@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . config('app.name', 'Matrimony') : config('app.name', 'Matrimony') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-pink-600 tracking-tight">
                        Matrimony
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('/') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }} transition">
                        Home
                    </a>
                   
                    <a href="{{ route('root.about') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('root.about') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }} transition">
                        About Us
                    </a>

                     <a href="{{ route('root.matrimony') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('root.matrimony') ? 'text-white bg-pink-600' : 'text-white bg-pink-500 hover:bg-pink-600' }} transition">
                        Profiles
                    </a>
                </div>

                <!-- Auth Buttons + Language Toggle -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-pink-600 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition">
                            Register
                        </a>
                    @else
                        @if (auth()->user()->canAccessProfileManagementPanel())
                            <a href="{{ route('admin') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-pink-600 transition">
                                Admin
                            </a>
                        @endif
                        @if (auth()->user()->canAccessContentManagement())
                            <a href="{{ route('admin.content-management') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-pink-600 transition">
                                Content Management
                            </a>
                        @endif
                        <a href="{{ route('account') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('account*') ? 'text-pink-600' : 'text-gray-600 hover:text-pink-600' }} transition">
                            My Account
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-pink-600 border border-pink-600 rounded-lg hover:bg-pink-600 hover:text-white transition">
                                Logout
                            </button>
                        </form>
                    @endguest

                    <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm p-1">
                        <button type="button" class="lang-toggle-btn px-3 py-1.5 text-xs font-semibold rounded-md transition-colors" data-lang-btn data-lang="en" aria-pressed="false">
                            English
                        </button>
                        <button type="button" class="lang-toggle-btn px-3 py-1.5 text-xs font-semibold rounded-md transition-colors" data-lang-btn data-lang="mr" aria-pressed="false">
                            मराठी
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-pink-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
            <div class="px-4 py-3 space-y-1">
                <div class="pt-1 pb-3">
                    <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm p-1 w-max">
                        <button type="button" class="lang-toggle-btn px-3 py-1.5 text-xs font-semibold rounded-md transition-colors" data-lang-btn data-lang="en" aria-pressed="false">
                            English
                        </button>
                        <button type="button" class="lang-toggle-btn px-3 py-1.5 text-xs font-semibold rounded-md transition-colors" data-lang-btn data-lang="mr" aria-pressed="false">
                            मराठी
                        </button>
                    </div>
                </div>
                <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('/') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">Home</a>
                <a href="{{ route('root.matrimony') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('root.matrimony') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">Matrimony</a>
                <a href="{{ route('root.about') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('root.about') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-pink-50' }}">About Us</a>
                @guest
                    <div class="pt-2 border-t border-gray-100 space-y-1">
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-pink-600 hover:bg-pink-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-center text-white bg-pink-600 hover:bg-pink-700">Register</a>
                    </div>
                @else
                    <div class="pt-2 border-t border-gray-100 space-y-1">
                        @if (auth()->user()->canAccessProfileManagementPanel())
                            <a href="{{ route('admin') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-pink-600 hover:bg-pink-50">Admin</a>
                        @endif
                        @if (auth()->user()->canAccessContentManagement())
                            <a href="{{ route('admin.content-management') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-pink-600 hover:bg-pink-50">Content Management</a>
                        @endif
                        <a href="{{ route('account') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-pink-600 hover:bg-pink-50">My Account</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-pink-600 hover:bg-pink-50">Logout</button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-pink-400 mb-3">Matrimony</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Helping you find your perfect life partner with trust, privacy, and care.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('root.matrimony') }}" class="hover:text-white transition">Matrimony</a></li>
                        <li><a href="{{ route('root.about') }}" class="hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>support@matrimony.com</li>
                        <li>+91 98765 43210</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Matrimony. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    <script>
        (function () {
            const STORAGE_KEY = 'matrimony_lang';
            const DEFAULT_LANG = 'mr';
            const activeClasses = 'bg-pink-100 text-pink-700 shadow-sm';
            const inactiveClasses = 'text-gray-500 hover:text-gray-900';

            function normalizeLang(lang) {
                return lang === 'mr' ? 'mr' : 'en';
            }

            function updateToggleStates(lang) {
                document.querySelectorAll('[data-lang-btn]').forEach(btn => {
                    const isActive = btn.dataset.lang === lang;
                    btn.classList.toggle('bg-pink-100', isActive);
                    btn.classList.toggle('text-pink-700', isActive);
                    btn.classList.toggle('shadow-sm', isActive);
                    btn.classList.toggle('text-gray-500', !isActive);
                    btn.classList.toggle('hover:text-gray-900', !isActive);
                    btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                const legacyEn = document.getElementById('lang-btn-en');
                const legacyMr = document.getElementById('lang-btn-mr');
                if (legacyEn && legacyMr) {
                    if (lang === 'mr') {
                        legacyMr.className = `px-3 py-1.5 text-xs font-semibold rounded-md transition-colors ${activeClasses}`;
                        legacyEn.className = `px-3 py-1.5 text-xs font-semibold rounded-md transition-colors ${inactiveClasses}`;
                    } else {
                        legacyEn.className = `px-3 py-1.5 text-xs font-semibold rounded-md transition-colors ${activeClasses}`;
                        legacyMr.className = `px-3 py-1.5 text-xs font-semibold rounded-md transition-colors ${inactiveClasses}`;
                    }
                }
            }

            function applyLanguage(lang) {
                const normalized = normalizeLang(lang);
                document.documentElement.setAttribute('lang', normalized);

                document.querySelectorAll('.lang-label').forEach(el => {
                    const text = normalized === 'mr' ? el.dataset.mr : el.dataset.en;
                    if (text) el.textContent = text;
                });

                document.querySelectorAll('[data-placeholder-en],[data-placeholder-mr]').forEach(el => {
                    const text = normalized === 'mr' ? el.dataset.placeholderMr : el.dataset.placeholderEn;
                    if (typeof text !== 'undefined') el.setAttribute('placeholder', text);
                });

                document.querySelectorAll('[data-alt-en],[data-alt-mr]').forEach(el => {
                    const text = normalized === 'mr' ? el.dataset.altMr : el.dataset.altEn;
                    if (typeof text !== 'undefined') el.setAttribute('alt', text);
                });

                updateToggleStates(normalized);
            }

            function setLanguage(lang) {
                const normalized = normalizeLang(lang);
                localStorage.setItem(STORAGE_KEY, normalized);
                applyLanguage(normalized);
            }

            function initLanguageToggle() {
                let saved = localStorage.getItem(STORAGE_KEY);
                if (!saved) {
                    saved = DEFAULT_LANG;
                    localStorage.setItem(STORAGE_KEY, saved);
                }
                applyLanguage(saved);

                document.querySelectorAll('[data-lang-btn]').forEach(btn => {
                    btn.addEventListener('click', () => setLanguage(btn.dataset.lang));
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initLanguageToggle);
            } else {
                initLanguageToggle();
            }

            window.setLanguage = setLanguage;
        })();
    </script>
</body>
</html>
