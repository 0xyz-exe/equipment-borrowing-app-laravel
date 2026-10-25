<?php

use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('equipment', EquipmentController::class);
    Route::get('/borrow-requests', [BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::get('/equipment/{equipment}/borrow', [BorrowRequestController::class, 'create'])->name('borrow-requests.create');
    Route::post('/borrow-requests', [BorrowRequestController::class, 'store'])->name('borrow-requests.store');
    Route::post('/borrow-requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::post('/borrow-requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::post('/borrow-requests/{borrowRequest}/return', [BorrowRequestController::class, 'returnEquipment'])->name('borrow-requests.return');
    Route::delete('/borrow-requests/{borrowRequest}', [BorrowRequestController::class, 'destroy'])->name('borrow-requests.destroy');
});

require __DIR__.'/auth.php';
