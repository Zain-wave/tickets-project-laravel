<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService
    ) {}

    public function index(): JsonResponse
    {
        $tickets = $this->ticketService->getAll(request()->all());

        return response()->json([
            'data' => $tickets->items(),
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->findById($id);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'data' => $ticket,
        ]);
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->create($request->validated());

        return response()->json([
            'message' => 'Ticket created successfully',
            'data' => $ticket,
        ], 201);
    }

    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        $ticket = $this->ticketService->update($id, $request->validated());

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket updated successfully',
            'data' => $ticket,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->ticketService->delete($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket deleted successfully',
        ]);
    }
}
