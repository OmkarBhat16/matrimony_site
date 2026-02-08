<x-admin-layout>
    <x-slot:title>Users</x-slot:title>
    <x-slot:header>User Administration</x-slot:header>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">All Users</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                    {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->approved)
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">Approved</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if (!$user->approved)
                                    <form action="{{ route('users.approve', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-4 py-1.5 rounded-lg transition">
                                            Approve
                                        </button>
                                    </form>
                                @else
                                    @if ($user->approved_by)
                                        <span class="text-xs text-gray-400">Approved by #{{ $user->approved_by }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>

