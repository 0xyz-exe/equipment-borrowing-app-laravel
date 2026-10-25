<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Borrow: {{ $equipment->name }}</h2></x-slot>
    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        @if($errors->has('error'))<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $errors->first('error') }}</div>@endif
        <div class="mb-4 p-3 bg-gray-50 rounded text-sm text-gray-600">
            <p><strong>Code:</strong> {{ $equipment->code }} &nbsp;|&nbsp; <strong>Category:</strong> {{ $equipment->category }} &nbsp;|&nbsp; <strong>Condition:</strong> {{ $equipment->condition }}</p>
        </div>
        <form action="{{ route('borrow-requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
            <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Purpose *</label><textarea name="purpose" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('purpose') }}</textarea>@error('purpose')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div><label class="block text-sm font-medium text-gray-700">Borrow Date *</label><input type="date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>@error('borrow_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700">Return Date *</label><input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d', strtotime('+7 days'))) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>@error('return_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit Request</button>
                <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</a>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
