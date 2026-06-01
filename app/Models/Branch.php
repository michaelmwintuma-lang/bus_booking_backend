<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}