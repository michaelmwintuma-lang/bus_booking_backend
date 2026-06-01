<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'price',
        'departure_time',
        'duration_minutes',
        'is_active',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}