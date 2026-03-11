<x-admin-layout>
    <x-slot:title>Review Edit — {{ $edit->user->name }}</x-slot:title>
    <x-slot:header>Edit Review</x-slot:header>

    <div class="max-w-4xl mx-auto">
        <!-- User Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $edit->user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Phone: {{ $edit->user->phone_number }}
                        · Submitted {{ $edit->created_at->diffForHumans() }}
                    </p>
                </div>
                <a href="{{ route('admin.pending-edits') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    &larr; Back to List
                </a>
            </div>
        </div>

        @if(empty($diff))
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <p class="text-sm text-gray-500">No changes detected — the edit is identical to the current profile.</p>
                <form action="{{ route('admin.pending-edits.reject', $edit) }}" method="POST" class="mt-4 inline">
                    @csrf
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">Dismiss</button>
                </form>
            </div>
        @else
            <!-- Diff Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Changes ({{ count($diff) }} field{{ count($diff) > 1 ? 's' : '' }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/4">Field</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-[37.5%]">Current</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-[37.5%]">Proposed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($diff as $field => $change)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $change['label'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="bg-red-50 text-red-700 px-2 py-1 rounded-md whitespace-pre-line">{{ $change['old'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="bg-green-50 text-green-700 px-2 py-1 rounded-md whitespace-pre-line">{{ $change['new'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mb-8">
                <form action="{{ route('admin.pending-edits.reject', $edit) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition shadow-sm">
                        Reject
                    </button>
                </form>
                <form action="{{ route('admin.pending-edits.approve', $edit) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition shadow-sm">
                        Approve Changes
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-admin-layout>
