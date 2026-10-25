<?php

namespace Database\Seeders;

use App\Models\BorrowRequest;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create(['name' => 'Admin', 'email' => 'admin@example.com', 'is_admin' => true]);
        $user  = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

        $items = [
            ['name' => 'Projector Epson EB-X41', 'code' => 'PRJ-001', 'category' => 'AV Equipment', 'condition' => 'Good'],
            ['name' => 'Laptop Dell Inspiron 15', 'code' => 'LPT-001', 'category' => 'Computing', 'condition' => 'Good'],
            ['name' => 'Tripod Manfrotto', 'code' => 'TRP-001', 'category' => 'Photography', 'condition' => 'Fair'],
            ['name' => 'Canon DSLR 800D', 'code' => 'CAM-001', 'category' => 'Photography', 'condition' => 'Good'],
            ['name' => 'Wireless Microphone', 'code' => 'MIC-001', 'category' => 'AV Equipment', 'condition' => 'Good'],
            ['name' => 'Extension Cord 10m', 'code' => 'EXT-001', 'category' => 'Tools', 'condition' => 'Good', 'is_available' => false],
        ];

        foreach ($items as $item) {
            Equipment::create($item);
        }

        // Pending request
        BorrowRequest::create(['user_id' => $user->id, 'equipment_id' => 1, 'purpose' => 'Department presentation next Monday', 'borrow_date' => now()->addDay(), 'return_date' => now()->addDays(3), 'status' => 'pending']);
        // Approved request (in use)
        BorrowRequest::create(['user_id' => $user->id, 'equipment_id' => 6, 'purpose' => 'Lab wiring setup', 'borrow_date' => now()->subDay(), 'return_date' => now()->addDays(2), 'status' => 'approved']);
    }
}
