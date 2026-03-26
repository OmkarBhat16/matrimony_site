<x-admin-layout>
    <x-slot:title>Pending Edits</x-slot:title>
    <x-slot:header>Pending Profile Edits</x-slot:header>


    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Edit Requests</h2>
            <p class="text-sm text-gray-500 mt-1">Users who have requested profile changes or photo replacements.</p>
        </div>

        @if($edits->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-500">No pending edit requests.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($edits as $edit)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $edit->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $edit->user->phone_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $edit->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pending-edits.review', $edit) }}"
                                       class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg transition">
                                        Review Changes
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin-layout>
