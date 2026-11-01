<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    public const STATUS_EMPTY = 'kosong';
    public const STATUS_BOOKED = 'dipesan';
    public const STATUS_OCCUPIED = 'terisi';
    public const STATUS_REPAIR = 'perbaikan';

    protected $fillable = [
        'room_number',
        'type',
        'price_per_month',
        'facilities',
        'status',
    ];

    protected function casts(): array
    {
        return ['price_per_month' => 'integer'];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
