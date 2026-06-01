<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        return response()->json(Route::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin'           => 'required|string',
            'destination'      => 'required|string',
            'price'            => 'required|numeric|min:0',
            'departure_time'   => 'required',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $route = Route::create($request->all());

        return response()->json([
            'message' => 'Route created successfully',
            'route'   => $route,
        ], 201);
    }

    public function show(Route $route)
    {
        return response()->json($route);
    }

    public function update(Request $request, Route $route)
    {
        $route->update($request->all());

        return response()->json([
            'message' => 'Route updated successfully',
            'route'   => $route,
        ]);
    }

    public function destroy(Route $route)
    {
        $route->delete();

        return response()->json(['message' => 'Route deleted successfully']);
    }
}