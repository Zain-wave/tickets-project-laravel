<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'device_type',
        'brand',
        'model',
        'serial_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'device_type' => 'string',
            'status' => 'string',
        ];
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at');
    }
}
