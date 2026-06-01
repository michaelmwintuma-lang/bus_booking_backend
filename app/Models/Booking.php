<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'route_id',
        'bus_id',
        'seat_id',
        'branch_id',
        'travel_date',
        'amount_paid',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}