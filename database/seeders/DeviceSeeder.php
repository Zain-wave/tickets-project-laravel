<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::create([
            'name' => 'Dell OptiPlex 3090',
            'device_type' => 'pc',
            'brand' => 'Dell',
            'model' => 'OptiPlex 3090',
            'serial_number' => 'SN-PC-001',
            'status' => 'assigned',
        ]);

        Device::create([
            'name' => 'MacBook Pro 16"',
            'device_type' => 'laptop',
            'brand' => 'Apple',
            'model' => 'MacBook Pro 2023',
            'serial_number' => 'SN-LAP-001',
            'status' => 'assigned',
        ]);

        Device::create([
            'name' => 'iPhone 15 Pro',
            'device_type' => 'mobile',
            'brand' => 'Apple',
            'model' => 'iPhone 15 Pro',
            'serial_number' => 'SN-MOB-001',
            'status' => 'available',
        ]);

        Device::create([
            'name' => 'iPad Air',
            'device_type' => 'tablet',
            'brand' => 'Apple',
            'model' => 'iPad Air M2',
            'serial_number' => 'SN-TAB-001',
            'status' => 'maintenance',
        ]);

        Device::create([
            'name' => 'HP LaserJet Pro',
            'device_type' => 'other',
            'brand' => 'HP',
            'model' => 'LaserJet Pro M404',
            'serial_number' => 'SN-OTH-001',
            'status' => 'available',
        ]);
    }
}
