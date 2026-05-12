<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Device\AssignDeviceRequest;
use App\Http\Requests\Device\StoreDeviceRequest;
use App\Models\Device;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function __construct(
        private readonly DeviceService $deviceService
    ) {}

    public function index(): JsonResponse
    {
        $devices = $this->deviceService->getAll(request()->all());

        return response()->json([
            'data' => $devices->items(),
            'meta' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $device = Device::with(['currentAssignment.user', 'tickets'])->find($id);

        if (!$device) {
            return response()->json([
                'message' => 'Device not found',
            ], 404);
        }

        return response()->json([
            'data' => $device,
        ]);
    }

    public function store(StoreDeviceRequest $request): JsonResponse
    {
        $device = Device::create($request->validated());

        return response()->json([
            'message' => 'Device created successfully',
            'data' => $device,
        ], 201);
    }

    public function assign(AssignDeviceRequest $request): JsonResponse
    {
        $assignment = $this->deviceService->assign($request->validated());

        return response()->json([
            'message' => 'Device assigned successfully',
            'data' => $assignment,
        ], 201);
    }

    public function return(int $assignmentId): JsonResponse
    {
        $assignment = $this->deviceService->return($assignmentId);

        if (!$assignment) {
            return response()->json([
                'message' => 'Assignment not found or already returned',
            ], 404);
        }

        return response()->json([
            'message' => 'Device returned successfully',
            'data' => $assignment,
        ]);
    }

    public function history(int $id): JsonResponse
    {
        $history = $this->deviceService->getHistory($id);

        return response()->json([
            'data' => $history->items(),
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ],
        ]);
    }
}
