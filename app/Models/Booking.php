<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const STATUS_BOOKED = 'dipesan';
    public const STATUS_CANCELLED = 'dibatalkan';

    protected $fillable = [
        'room_id',
        'tenant_id',
        'start_date',
        'duration_months',
        'down_payment',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'duration_months' => 'integer',
            'down_payment' => 'integer',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
