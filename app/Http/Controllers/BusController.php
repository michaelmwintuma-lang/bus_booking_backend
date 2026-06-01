<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        return response()->json(Bus::with('seats')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_number'  => 'required|string|unique:buses',
            'type'        => 'required|string',
            'total_seats' => 'required|integer|min:1',
        ]);

        $bus = Bus::create($request->all());

        return response()->json([
            'message' => 'Bus created successfully',
            'bus'     => $bus,
        ], 201);
    }

    public function show(Bus $bus)
    {
        return response()->json($bus->load('seats'));
    }

    public function update(Request $request, Bus $bus)
    {
        $bus->update($request->all());

        return response()->json([
            'message' => 'Bus updated successfully',
            'bus'     => $bus,
        ]);
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();

        return response()->json(['message' => 'Bus deleted successfully']);
    }
}