<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['route', 'bus', 'seat'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_id'    => 'required|exists:routes,id',
            'bus_id'      => 'required|exists:buses,id',
            'seat_id'     => 'required|exists:seats,id',
            'travel_date' => 'required|date|after_or_equal:today',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $seat = Seat::findOrFail($request->seat_id);

        if (!$seat->is_available) {
            return response()->json([
                'message' => 'Seat is already booked',
            ], 422);
        }

        $booking = Booking::create([
            'user_id'     => $request->user()->id,
            'route_id'    => $request->route_id,
            'bus_id'      => $request->bus_id,
            'seat_id'     => $request->seat_id,
            'travel_date' => $request->travel_date,
            'amount_paid' => $request->amount_paid,
            'status'      => 'confirmed',
        ]);

        $seat->update(['is_available' => false]);

        return response()->json([
            'message' => 'Booking confirmed successfully',
            'booking' => $booking->load(['route', 'bus', 'seat']),
        ], 201);
    }

    public function show(Booking $booking)
    {
        return response()->json($booking->load(['route', 'bus', 'seat']));
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->only('status'));

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking,
        ]);
    }

    public function destroy(Booking $booking)
    {
        $seat = Seat::findOrFail($booking->seat_id);
        $seat->update(['is_available' => true]);

        $booking->delete();

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
    /**
     * Get current user's bookings
     */
    public function myBookings(Request $request)
    {
        $user = $request->user();
        
        $bookings = Booking::where('user_id', $user->id)
            ->with(['bus', 'route', 'seat'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'User bookings retrieved successfully',
            'data' => $bookings
        ], 200);
    }
}