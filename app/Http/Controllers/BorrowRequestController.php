<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;

class BorrowRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $requests = BorrowRequest::with('user', 'equipment')->latest()->paginate(20);
        } else {
            $requests = $user->borrowRequests()->with('equipment')->latest()->paginate(20);
        }
        return view('borrow-requests.index', compact('requests'));
    }

    public function create(Equipment $equipment)
    {
        if (!$equipment->is_available) {
            return back()->withErrors(['error' => 'This equipment is not available.']);
        }
        return view('borrow-requests.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'purpose'      => 'required|string|max:500',
            'borrow_date'  => 'required|date|after_or_equal:today',
            'return_date'  => 'required|date|after:borrow_date',
        ]);
        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';
        BorrowRequest::create($validated);
        return redirect()->route('borrow-requests.index')->with('success', 'Request submitted. Awaiting approval.');
    }

    public function approve(BorrowRequest $borrowRequest)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $borrowRequest->update(['status' => 'approved']);
        $borrowRequest->equipment->update(['is_available' => false]);
        return back()->with('success', 'Request approved.');
    }

    public function reject(Request $request, BorrowRequest $borrowRequest)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $borrowRequest->update([
            'status'      => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);
        return back()->with('success', 'Request rejected.');
    }

    public function returnEquipment(BorrowRequest $borrowRequest)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $borrowRequest->update(['status' => 'returned', 'actual_return_date' => now()]);
        $borrowRequest->equipment->update(['is_available' => true]);
        return back()->with('success', 'Equipment marked as returned.');
    }

    public function destroy(BorrowRequest $borrowRequest)
    {
        abort_unless(auth()->id() === $borrowRequest->user_id && $borrowRequest->status === 'pending', 403);
        $borrowRequest->delete();
        return redirect()->route('borrow-requests.index')->with('success', 'Request cancelled.');
    }
}
