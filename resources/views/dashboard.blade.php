<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipment Management Dashboard') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('equipment.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">Browse Catalog</a>
                <a href="{{ route('borrow-requests.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">View Requests</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Metric Cards -->
            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Equipment</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Equipment::count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Catalog items registered</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Available Ready</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ \App\Models\Equipment::where('is_available', true)->count() }}</p>
                    <p class="text-xs text-green-600 mt-1">Ready for borrowing</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wider">Pending Requests</p>
                    <p class="text-3xl font-bold text-yellow-700 mt-2">{{ \App\Models\BorrowRequest::where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Awaiting approval</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">In Use / Borrowed</p>
                    <p class="text-3xl font-bold text-blue-700 mt-2">{{ \App\Models\Equipment::where('is_available', false)->count() }}</p>
                    <p class="text-xs text-blue-600 mt-1">Currently on loan</p>
                </div>
            </div>

            <!-- Recent Borrow Requests -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-bold text-gray-900">Recent Loan Requests</h3>
                    <a href="{{ route('borrow-requests.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">View All →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrower</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse(\App\Models\BorrowRequest::with(['equipment', 'user'])->latest()->take(5)->get() as $req)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $req->equipment?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $req->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ \Illuminate\Support\Carbon::parse($req->borrow_date)->format('M d, Y') }} → {{ \Illuminate\Support\Carbon::parse($req->return_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">{{ $req->purpose }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $req->status === 'approved' ? 'bg-green-100 text-green-800' : ($req->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-4 text-center text-sm text-gray-500">No loan requests recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
