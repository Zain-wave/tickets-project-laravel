<?php

namespace Database\Seeders;

use App\Models\Assignment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        Assignment::create([
            'user_id' => 3,
            'device_id' => 1,
            'assigned_at' => Carbon::now()->subMonths(6),
            'returned_at' => null,
            'notes' => 'Main workstation for daily operations',
        ]);

        Assignment::create([
            'user_id' => 3,
            'device_id' => 2,
            'assigned_at' => Carbon::now()->subMonths(3),
            'returned_at' => null,
            'notes' => 'Portable device for remote work',
        ]);

        Assignment::create([
            'user_id' => 2,
            'device_id' => 4,
            'assigned_at' => Carbon::now()->subMonth(),
            'returned_at' => Carbon::now()->subDays(5),
            'notes' => 'Temporary assignment for testing',
        ]);
    }
}
