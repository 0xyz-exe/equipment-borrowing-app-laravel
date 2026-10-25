@php $editing = isset($equipment); $action = $editing ? route('equipment.update', $equipment) : route('equipment.store'); @endphp
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $editing ? 'Edit' : 'Add' }} Equipment</h2></x-slot>
    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form action="{{ $action }}" method="POST">
            @csrf @if($editing) @method('PUT') @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700">Name *</label><input type="text" name="name" value="{{ old('name', $equipment->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>@error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700">Code *</label><input type="text" name="code" value="{{ old('code', $equipment->code ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>@error('code')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700">Category *</label><input type="text" name="category" value="{{ old('category', $equipment->category ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>@error('category')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700">Condition *</label>
                    <select name="condition" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach(['Good','Fair','Poor'] as $c)<option value="{{ $c }}" {{ old('condition', $equipment->condition ?? 'Good') === $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach
                    </select></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Description</label><textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $equipment->description ?? '') }}</textarea></div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ $editing ? 'Update' : 'Save' }}</button>
                <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</a>
            </div>
        </form>
    </div></div></div>
</x-app-layout>
