<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowRequest extends Model
{
    protected $fillable = [
        'user_id', 'equipment_id', 'purpose',
        'borrow_date', 'return_date', 'actual_return_date',
        'status', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date'        => 'date',
            'return_date'        => 'date',
            'actual_return_date' => 'date',
        ];
    }

    public function user(): BelongsTo      { return $this->belongsTo(User::class); }
    public function equipment(): BelongsTo { return $this->belongsTo(Equipment::class); }

    public function isOverdue(): bool
    {
        return $this->status === 'approved' && $this->return_date->isPast();
    }

    public function statusBadge(): string
    {
        return match($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'returned' => 'bg-gray-100 text-gray-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }
}
