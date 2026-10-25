<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Equipment</h2>
            <div class="flex gap-2">
                <a href="{{ route('borrow-requests.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">{{ auth()->user()->isAdmin() ? 'All Requests' : 'My Requests' }}</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('equipment.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Equipment</a>
                @endif
            </div>
        </div>
    </x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>@endif
        @if($errors->has('error'))<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $errors->first('error') }}</div>@endif
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="GET" class="mb-4 flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, code, category..." class="flex-1 rounded-md border-gray-300 shadow-sm">
                <select name="category" class="rounded-md border-gray-300 shadow-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>@endforeach
                </select>
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                @if(request()->hasAny(['search','category']))<a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Clear</a>@endif
            </form>
            @if($equipment->isEmpty())<p class="text-gray-500 text-center py-8">No equipment found.</p>@else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Condition</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr></thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($equipment as $item)<tr>
                            <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $item->code }}</td>
                            <td class="px-4 py-3"><div class="font-medium text-sm">{{ $item->name }}</div>@if($item->description)<div class="text-xs text-gray-500 truncate max-w-xs">{{ $item->description }}</div>@endif</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->category }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $item->condition }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $item->is_available ? 'Available' : 'In Use' }}</span></td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                @if($item->is_available)<a href="{{ route('borrow-requests.create', $item) }}" class="text-blue-600 hover:underline">Borrow</a>@endif
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('equipment.edit', $item) }}" class="text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('equipment.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                                @endif
                            </td>
                        </tr>@endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $equipment->links() }}</div>
            @endif
        </div>
    </div></div>
</x-app-layout>
