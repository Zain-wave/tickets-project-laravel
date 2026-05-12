<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;

class DeviceService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function getAll(array $filters = [])
    {
        $query = Device::query();

        if (!empty($filters['device_type'])) {
            $query->where('device_type', $filters['device_type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('serial_number', 'like', "%{$filters['search']}%")
                  ->orWhere('brand', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function assign(array $data): Assignment
    {
        $assignment = Assignment::create([
            'user_id' => $data['user_id'],
            'device_id' => $data['device_id'],
            'assigned_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        Device::where('id', $data['device_id'])->update(['status' => 'assigned']);

        $this->activityLogService->log(
            'device_assigned',
            "Device #{$data['device_id']} assigned to user #{$data['user_id']}",
            ['device_id' => $data['device_id'], 'user_id' => $data['user_id']],
        );

        return $assignment->load(['user', 'device']);
    }

    public function return(int $assignmentId): ?Assignment
    {
        $assignment = Assignment::with(['user', 'device'])->find($assignmentId);

        if (!$assignment || $assignment->returned_at) {
            return null;
        }

        $assignment->update(['returned_at' => now()]);

        Device::where('id', $assignment->device_id)->update(['status' => 'available']);

        $this->activityLogService->log(
            'device_returned',
            "Device #{$assignment->device_id} returned by user #{$assignment->user_id}",
            ['device_id' => $assignment->device_id, 'user_id' => $assignment->user_id],
        );

        return $assignment->fresh()->load(['user', 'device']);
    }

    public function getHistory(int $deviceId)
    {
        return Assignment::with(['user'])
            ->where('device_id', $deviceId)
            ->latest('assigned_at')
            ->paginate(15);
    }
}
