<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ auth()->user()->isAdmin() ? 'All Borrow Requests' : 'My Requests' }}</h2>
            <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Equipment List</a>
        </div>
    </x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>@endif
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if($requests->isEmpty())<p class="text-gray-500 text-center py-8">No requests found.</p>@else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        @if(auth()->user()->isAdmin())<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>@endif
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr></thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $req)<tr>
                            @if(auth()->user()->isAdmin())<td class="px-4 py-3 text-sm">{{ $req->user->name }}</td>@endif
                            <td class="px-4 py-3 text-sm"><div class="font-medium">{{ $req->equipment->name }}</div><div class="text-xs text-gray-500">{{ $req->equipment->code }}</div></td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">{{ $req->purpose }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $req->borrow_date->format('M d') }} → {{ $req->return_date->format('M d, Y') }}
                                @if($req->isOverdue())<span class="ml-1 text-xs text-red-600 font-semibold">OVERDUE</span>@endif</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded {{ $req->statusBadge() }}">{{ ucfirst($req->status) }}</span></td>
                            <td class="px-4 py-3 text-sm space-x-1">
                                @if(auth()->user()->isAdmin())
                                    @if($req->status === 'pending')
                                        <form action="{{ route('borrow-requests.approve', $req) }}" method="POST" class="inline">@csrf<button type="submit" class="text-green-600 hover:underline">Approve</button></form>
                                        <form action="{{ route('borrow-requests.reject', $req) }}" method="POST" class="inline">@csrf<button type="submit" class="text-red-600 hover:underline">Reject</button></form>
                                    @elseif($req->status === 'approved')
                                        <form action="{{ route('borrow-requests.return', $req) }}" method="POST" class="inline">@csrf<button type="submit" class="text-blue-600 hover:underline">Mark Returned</button></form>
                                    @endif
                                @elseif($req->status === 'pending')
                                    <form action="{{ route('borrow-requests.destroy', $req) }}" method="POST" class="inline" onsubmit="return confirm('Cancel request?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Cancel</button></form>
                                @endif
                            </td>
                        </tr>@endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $requests->links() }}</div>
            @endif
        </div>
    </div></div>
</x-app-layout>
