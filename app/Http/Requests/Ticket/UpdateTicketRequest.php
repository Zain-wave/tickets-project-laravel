<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:open,in_progress,resolved,closed',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'device_id' => 'nullable|integer|exists:devices,id',
        ];
    }
}
