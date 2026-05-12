<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create([
            'user_id' => 3,
            'assigned_to' => 2,
            'title' => 'Keyboard not working',
            'description' => 'The keyboard on my Dell desktop stopped working after the latest update.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'device_id' => 1,
        ]);

        Ticket::create([
            'user_id' => 3,
            'assigned_to' => 2,
            'title' => 'Request new laptop charger',
            'description' => 'My MacBook charger is damaged and needs replacement.',
            'status' => 'open',
            'priority' => 'low',
            'device_id' => 2,
        ]);

        Ticket::create([
            'user_id' => 3,
            'assigned_to' => null,
            'title' => 'WiFi connectivity issues',
            'description' => 'Frequent disconnections from the office WiFi network.',
            'status' => 'open',
            'priority' => 'high',
            'device_id' => null,
        ]);

        Ticket::create([
            'user_id' => 1,
            'assigned_to' => 2,
            'title' => 'Replace iPad screen',
            'description' => 'The iPad Air screen has a crack on the top left corner.',
            'status' => 'resolved',
            'priority' => 'medium',
            'device_id' => 4,
        ]);

        Ticket::create([
            'user_id' => 2,
            'assigned_to' => 1,
            'title' => 'Server room temperature alert',
            'description' => 'The monitoring system detected high temperature in the server room.',
            'status' => 'closed',
            'priority' => 'critical',
            'device_id' => null,
        ]);
    }
}
