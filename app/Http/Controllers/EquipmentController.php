<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();
        if ($s = $request->get('search')) {
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('code', 'like', "%$s%")->orWhere('category', 'like', "%$s%"));
        }
        if ($cat = $request->get('category')) $query->where('category', $cat);
        $equipment = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Equipment::distinct()->pluck('category');
        return view('equipment.index', compact('equipment', 'categories'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|unique:equipment,code|max:50',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'condition'   => 'required|in:Good,Fair,Poor',
        ]);
        Equipment::create($validated);
        return redirect()->route('equipment.index')->with('success', 'Equipment added.');
    }

    public function edit(Equipment $equipment)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:equipment,code,' . $equipment->id,
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'condition'   => 'required|in:Good,Fair,Poor',
        ]);
        $equipment->update($validated);
        return redirect()->route('equipment.index')->with('success', 'Equipment updated.');
    }

    public function destroy(Equipment $equipment)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        if ($equipment->borrowRequests()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->withErrors(['error' => 'Cannot delete equipment with active requests.']);
        }
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted.');
    }
}
