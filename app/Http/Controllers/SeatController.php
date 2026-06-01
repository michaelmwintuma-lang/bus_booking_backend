<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Display a listing of seats
     */
    public function index(Request $request)
    {
        $query = Seat::query();

        // Filter by bus_id if provided
        if ($request->has('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        // Filter by route_id if provided
        if ($request->has('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        // Filter by availability
        if ($request->has('available')) {
            $available = $request->boolean('available');
            $query->where('is_available', $available);
        }

        $seats = $query->get();

        return response()->json([
            'message' => 'Seats retrieved successfully',
            'data' => $seats
        ], 200);
    }

    /**
     * Store a newly created seat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'seat_number' => 'required|string|unique:seats,seat_number',
            'is_available' => 'boolean',
        ]);

        $seat = Seat::create($validated);

        return response()->json([
            'message' => 'Seat created successfully',
            'data' => $seat
        ], 201);
    }

    /**
     * Display the specified seat
     */
    public function show(Seat $seat)
    {
        return response()->json([
            'message' => 'Seat retrieved successfully',
            'data' => $seat
        ], 200);
    }

    /**
     * Update the specified seat
     */
    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'seat_number' => 'string|unique:seats,seat_number,' . $seat->id,
            'is_available' => 'boolean',
        ]);

        $seat->update($validated);

        return response()->json([
            'message' => 'Seat updated successfully',
            'data' => $seat
        ], 200);
    }

    /**
     * Remove the specified seat
     */
    public function destroy(Seat $seat)
    {
        $seat->delete();

        return response()->json([
            'message' => 'Seat deleted successfully'
        ], 200);
    }
}