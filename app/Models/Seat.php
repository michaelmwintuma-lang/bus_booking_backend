<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'seat_number',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the bus that owns the seat
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the route that owns the seat
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get bookings for this seat
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}