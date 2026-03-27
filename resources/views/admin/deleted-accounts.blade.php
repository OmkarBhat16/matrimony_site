<x-admin-layout>
    <x-slot:title>Deleted Accounts</x-slot:title>
    <x-slot:header>Deleted Accounts</x-slot:header>

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Soft-deleted accounts</h2>
        <p class="mt-1 text-sm text-gray-500">Restore or permanently delete accounts from here.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if ($deletedUsers->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-500">
                No deleted accounts found.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Deleted At</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($deletedUsers as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-400">#{{ $user->public_id ?? $user->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($user->role) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->deleted_at?->diffForHumans() ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <form action="{{ route('admin.deleted-accounts.restore', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-4 py-1.5 rounded-lg transition">
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.deleted-accounts.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this account? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-4 py-1.5 rounded-lg transition">
                                                Permanently Delete
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
</x-admin-layout>
