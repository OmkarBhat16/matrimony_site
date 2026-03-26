<x-admin-layout>
    <x-slot:title>Users</x-slot:title>
    <x-slot:header>User Administration</x-slot:header>

    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.users', ['tab' => 'registrations']) }}"
               class="whitespace-nowrap pb-3 px-1 border-b-2 text-sm font-medium transition
                   {{ $tab === 'registrations' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Registrations
                @if($registrations->count())
                    <span class="ml-1.5 px-2 py-0.5 text-xs font-semibold rounded-full {{ $tab === 'registrations' ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-500' }}">{{ $registrations->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users', ['tab' => 'pending']) }}"
               class="whitespace-nowrap pb-3 px-1 border-b-2 text-sm font-medium transition
                   {{ $tab === 'pending' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Pending Review
                @if($pendingReview->count())
                    <span class="ml-1.5 px-2 py-0.5 text-xs font-semibold rounded-full {{ $tab === 'pending' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-500' }}">{{ $pendingReview->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users', ['tab' => 'approved']) }}"
               class="whitespace-nowrap pb-3 px-1 border-b-2 text-sm font-medium transition
                   {{ $tab === 'approved' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Approved
                @if($approved->count())
                    <span class="ml-1.5 px-2 py-0.5 text-xs font-semibold rounded-full {{ $tab === 'approved' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">{{ $approved->count() }}</span>
                @endif
            </a>
        </nav>
    </div>

    {{-- Flash: generated password --}}
    @if(session('generated_password'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-sm font-medium text-green-800 mb-2">
                {{ session('success') ?? 'Password generated! Share this password with the user:' }}
            </p>
            <div class="flex items-center gap-3">
                <code id="generated-pw" class="flex-1 px-4 py-2.5 bg-white border border-green-300 rounded-lg text-lg font-mono tracking-wider text-gray-900 select-all">{{ session('generated_password') }}</code>
                <button type="button" onclick="copyPassword()" id="copy-btn"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span id="copy-text">Copy</span>
                </button>
            </div>
        </div>
    @endif



    {{-- ========== REGISTRATIONS TAB ========== --}}
    @if($tab === 'registrations')
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">New Registrations</h2>
                <p class="text-sm text-gray-500 mt-1">Users who have registered but don't have an account yet.</p>
            </div>
            @if($registrations->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-gray-500">No new registrations.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($registrations as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.users.create-account', $user) }}" method="POST" class="js-single-submit">
                                            @csrf
                                            <button type="submit" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-lg transition">
                                                Create Account
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    {{-- ========== PENDING REVIEW TAB ========== --}}
    @if($tab === 'pending')
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Profiles Pending Review</h2>
                <p class="text-sm text-gray-500 mt-1">Users who have submitted their full profile for approval.</p>
            </div>
            @if($pendingReview->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-gray-500">No profiles pending review.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pendingReview as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                    <td class="px-6 py-4 flex items-center gap-2">
                                        <a href="{{ route('admin.users.profile', $user) }}"
                                           class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg transition">
                                            View Profile
                                        </a>
                                        <form action="{{ route('users.approve', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-4 py-1.5 rounded-lg transition">
                                                Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    {{-- ========== APPROVED TAB ========== --}}
    @if($tab === 'approved')
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Approved Users</h2>
            </div>
            @if($approved->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-gray-500">No approved users yet.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Profile</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($approved as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($user->profile)
                                                <a href="{{ route('admin.users.profile', $user) }}"
                                                   class="text-sm text-pink-600 hover:text-pink-700 font-medium">View</a>
                                            @endif
                                            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 px-4 py-1.5 rounded-lg transition">
                                                    Reset Password
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <script>
        document.querySelectorAll('.js-single-submit').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('button[type="submit"]');

                if (!button) {
                    return;
                }

                button.disabled = true;
                button.classList.add('opacity-60', 'cursor-not-allowed');
                button.textContent = 'Creating...';
            });
        });

        function copyPassword() {
            const pw = document.getElementById('generated-pw').textContent;
            navigator.clipboard.writeText(pw).then(() => {
                const btn = document.getElementById('copy-text');
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 2000);
            });
        }
    </script>
</x-admin-layout>
