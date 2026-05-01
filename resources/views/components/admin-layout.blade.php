<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    @php
        $user = auth()->user();
        $canManageProfiles = $user?->canAccessProfileManagementPanel() ?? false;
        $canManageContent = $user?->canAccessContentManagement() ?? false;
        $canManageDeletedAccounts = $user?->isSuperAdmin() ?? false;
    @endphp
    <div x-data="{ mobileMenuOpen: false }" class="min-h-screen bg-gray-100 lg:flex">
        <div x-cloak x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" @click="mobileMenuOpen = false" aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-800 text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0"
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex h-16 items-center justify-between bg-gray-900 px-5">
                <span class="text-lg font-bold">Admin Panel</span>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white lg:hidden" @click="mobileMenuOpen = false" aria-label="Close admin menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="mt-6 px-4 space-y-2 pb-8">
                @if ($canManageProfiles)
                    <a href="{{ route('admin') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.featured-profiles') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.featured-profiles*') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.featured-profiles*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.54 1.118l-3.368-2.447a1 1 0 00-1.175 0l-3.368 2.447c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L3.09 9.383c-.783-.57-.38-1.81.588-1.81H7.84a1 1 0 00.951-.69l1.257-3.956z"/>
                        </svg>
                        Featured Profiles
                    </a>
                    <a href="{{ route('admin.users') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.pending-edits') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.pending-edits*') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.pending-edits*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Pending Edits
                    </a>
                    <a href="{{ route('admin.settings') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.settings*') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                @endif

                @if ($canManageContent)
                    <a href="{{ route('admin.content-management') }}" class="group flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.content-management') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.content-management') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                        </svg>
                        Content Management
                    </a>
                @endif

                @if ($canManageDeletedAccounts)
                    @php $deletedAccountsCount = \App\Models\User::onlyTrashed()->count(); @endphp
                    <a href="{{ route('admin.deleted-accounts') }}" class="group flex items-center justify-between gap-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors hover:bg-gray-700 {{ request()->routeIs('admin.deleted-accounts*') ? 'bg-gray-700 text-white shadow-sm' : 'text-gray-300 hover:text-white' }}">
                        <span class="flex items-center">
                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.deleted-accounts*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14"/>
                        </svg>
                        Deleted Accounts
                        </span>
                        @if ($deletedAccountsCount > 0)
                            <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white">
                                {{ $deletedAccountsCount }}
                            </span>
                        @endif
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex min-h-screen w-full flex-1 flex-col lg:pl-72">
            <!-- Top Navigation -->
            <header class="sticky top-0 z-30 border-b border-gray-100 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:h-16 lg:py-0">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-700 hover:border-pink-300 hover:text-pink-600 lg:hidden" @click="mobileMenuOpen = true" aria-label="Open admin menu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <h1 class="truncate text-lg font-semibold text-gray-800 sm:text-xl">{{ $header ?? 'Dashboard' }}</h1>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-sm text-gray-600 sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 px-4 py-5 sm:px-6 lg:p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 shadow-sm">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
