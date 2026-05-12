<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {}

    public function getAll(array $filters = [])
    {
        $query = Ticket::with(['user', 'assignedTo', 'device']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id): ?Ticket
    {
        return Ticket::with(['user', 'assignedTo', 'device'])->find($id);
    }

    public function create(array $data): Ticket
    {
        $data['user_id'] = auth()->id();

        $ticket = Ticket::create($data);

        $this->activityLogService->log(
            'ticket_created',
            "Ticket #{$ticket->id} created: {$ticket->title}",
            ['ticket_id' => $ticket->id],
        );

        return $ticket->load(['user', 'assignedTo', 'device']);
    }

    public function update(int $id, array $data): ?Ticket
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return null;
        }

        $ticket->update($data);

        $this->activityLogService->log(
            'ticket_updated',
            "Ticket #{$ticket->id} updated",
            ['ticket_id' => $ticket->id, 'changes' => $data],
        );

        return $ticket->fresh()->load(['user', 'assignedTo', 'device']);
    }

    public function delete(int $id): bool
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return false;
        }

        $this->activityLogService->log(
            'ticket_deleted',
            "Ticket #{$ticket->id} deleted: {$ticket->title}",
            ['ticket_id' => $ticket->id],
        );

        return $ticket->delete();
    }
}
