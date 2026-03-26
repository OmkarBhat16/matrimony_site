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
            <a href="{{ route('admin.users', ['tab' => 'all']) }}"
               class="whitespace-nowrap pb-3 px-1 border-b-2 text-sm font-medium transition
                   {{ $tab === 'all' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All Users
                @if($totalUsersCount)
                    <span class="ml-1.5 px-2 py-0.5 text-xs font-semibold rounded-full {{ $tab === 'all' ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-500' }}">{{ $totalUsersCount }}</span>
                @endif
            </a>
        </nav>
    </div>

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
                                @if((int) session('generated_for_user') === $user->id && session('generated_password'))
                                    <tr class="bg-green-50/70">
                                        <td colspan="6" class="px-6 py-4">
                                            <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                                                <p class="text-sm font-medium text-green-800">
                                                    Share this password with {{ $user->name }} now. It will only be shown once.
                                                </p>
                                                <div class="mt-3 flex items-center gap-3">
                                                    <code id="generated-pw-{{ $user->id }}" class="flex-1 rounded-lg border border-green-300 bg-white px-4 py-2.5 text-lg tracking-wider text-gray-900">{{ session('generated_password') }}</code>
                                                    <button type="button"
                                                            data-password-target="generated-pw-{{ $user->id }}"
                                                            class="js-copy-password rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                                        <span>Copy</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                                @if((int) session('generated_for_user') === $user->id && session('generated_password'))
                                    <tr class="bg-green-50/70">
                                        <td colspan="4" class="px-6 py-4">
                                            <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                                                <p class="text-sm font-medium text-green-800">
                                                    Share this password with {{ $user->name }} now. It will only be shown once.
                                                </p>
                                                <div class="mt-3 flex items-center gap-3">
                                                    <code id="generated-pw-{{ $user->id }}" class="flex-1 rounded-lg border border-green-300 bg-white px-4 py-2.5 text-lg tracking-wider text-gray-900">{{ session('generated_password') }}</code>
                                                    <button type="button"
                                                            data-password-target="generated-pw-{{ $user->id }}"
                                                            class="js-copy-password rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                                        <span>Copy</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                                            @php $pendingEdit = $pendingEditsByUser[$user->id] ?? null; @endphp
                                            @if($pendingEdit)
                                                <a href="{{ route('admin.pending-edits.review', $pendingEdit) }}"
                                                   class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg transition">
                                                    Review Changes
                                                </a>
                                            @endif
                                        </div>
                                        @if($pendingEdit)
                                            <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                <span>Pending edit</span>
                                                <span>•</span>
                                                <span>{{ ucfirst($pendingEdit->edit_type) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if((int) session('generated_for_user') === $user->id && session('generated_password'))
                                    <tr class="bg-green-50/70">
                                        <td colspan="5" class="px-6 py-4">
                                            <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                                                <p class="text-sm font-medium text-green-800">
                                                    Share this password with {{ $user->name }} now. It will only be shown once.
                                                </p>
                                                <div class="mt-3 flex items-center gap-3">
                                                    <code id="generated-pw-{{ $user->id }}" class="flex-1 rounded-lg border border-green-300 bg-white px-4 py-2.5 text-lg tracking-wider text-gray-900">{{ session('generated_password') }}</code>
                                                    <button type="button"
                                                            data-password-target="generated-pw-{{ $user->id }}"
                                                            class="js-copy-password rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                                        <span>Copy</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    {{-- ========== ALL USERS TAB ========== --}}
    @if($tab === 'all')
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">All Users</h2>
                        <p class="text-sm text-gray-500 mt-1">Filter users by verification step or search by name, phone, or email.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <input type="hidden" name="tab" value="all">
                        <div>
                            <label for="step" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Step</label>
                            <select id="step" name="step" class="rounded-lg border-gray-300 text-sm focus:border-pink-500 focus:ring-pink-500">
                                <option value="all" @selected($stepFilter === 'all')>All steps</option>
                                <option value="unverified" @selected($stepFilter === 'unverified')>Unverified</option>
                                <option value="step1_complete" @selected($stepFilter === 'step1_complete')>Step 1 Complete</option>
                                <option value="step2_pending" @selected($stepFilter === 'step2_pending')>Step 2 Pending</option>
                                <option value="approved" @selected($stepFilter === 'approved')>Approved</option>
                            </select>
                        </div>
                        <div>
                            <label for="search" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Search</label>
                            <input id="search" name="search" type="text" value="{{ $search }}"
                                   placeholder="Name, phone, or email"
                                   class="rounded-lg border-gray-300 text-sm focus:border-pink-500 focus:ring-pink-500">
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 rounded-lg transition">
                                Apply
                            </button>
                            <a href="{{ route('admin.users', ['tab' => 'all']) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @if($allUsers->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-gray-500">No users matched the selected filters.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Step</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($allUsers as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                            @class([
                                                'bg-gray-100 text-gray-700' => $user->verification_step === 'unverified',
                                                'bg-blue-100 text-blue-700' => $user->verification_step === 'step1_complete',
                                                'bg-amber-100 text-amber-700' => $user->verification_step === 'step2_pending',
                                                'bg-green-100 text-green-700' => $user->verification_step === 'approved',
                                            ])">
                                            {{ str_replace('_', ' ', ucfirst($user->verification_step)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($user->verification_step === 'unverified')
                                                <form action="{{ route('admin.users.create-account', $user) }}" method="POST" class="js-single-submit">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-lg transition">
                                                        Create Account
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 px-4 py-1.5 rounded-lg transition">
                                                        Reset Password
                                                    </button>
                                                </form>
                                            @endif

                                            @if($user->profile)
                                                <a href="{{ route('admin.users.profile', $user) }}"
                                                   class="text-sm font-medium text-pink-600 hover:text-pink-700">
                                                    View Profile
                                                </a>
                                            @endif

                                            @if($user->verification_step === 'step2_pending')
                                                <form action="{{ route('users.approve', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-4 py-1.5 rounded-lg transition">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif

                                            @php $pendingEdit = $pendingEditsByUser[$user->id] ?? null; @endphp
                                            @if($pendingEdit)
                                                <a href="{{ route('admin.pending-edits.review', $pendingEdit) }}"
                                                   class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg transition">
                                                    Review Changes
                                                </a>
                                            @endif
                                        </div>
                                        @if($pendingEdit)
                                            <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                                <span>Pending edit</span>
                                                <span>•</span>
                                                <span>{{ ucfirst($pendingEdit->edit_type) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if((int) session('generated_for_user') === $user->id && session('generated_password'))
                                    <tr class="bg-green-50/70">
                                        <td colspan="6" class="px-6 py-4">
                                            <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                                                <p class="text-sm font-medium text-green-800">
                                                    Share this password with {{ $user->name }} now. It will only be shown once.
                                                </p>
                                                <div class="mt-3 flex items-center gap-3">
                                                    <code id="generated-pw-{{ $user->id }}" class="flex-1 rounded-lg border border-green-300 bg-white px-4 py-2.5 text-lg tracking-wider text-gray-900">{{ session('generated_password') }}</code>
                                                    <button type="button"
                                                            data-password-target="generated-pw-{{ $user->id }}"
                                                            class="js-copy-password rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                                        <span>Copy</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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

        document.querySelectorAll('.js-copy-password').forEach((button) => {
            button.addEventListener('click', async () => {
                const targetId = button.dataset.passwordTarget;
                const passwordField = document.getElementById(targetId);

                if (!passwordField) {
                    return;
                }

                await navigator.clipboard.writeText(passwordField.textContent);

                const label = button.querySelector('span');
                if (!label) {
                    return;
                }

                label.textContent = 'Copied!';
                setTimeout(() => {
                    label.textContent = 'Copy';
                }, 2000);
            });
        });
    </script>
</x-admin-layout>
