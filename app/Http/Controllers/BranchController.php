<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        return response()->json(Branch::with('users')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'location' => 'required|string',
        ]);

        $branch = Branch::create($request->all());

        return response()->json([
            'message' => 'Branch created successfully',
            'branch'  => $branch,
        ], 201);
    }

    public function show(Branch $branch)
    {
        return response()->json($branch->load(['users', 'bookings']));
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->update($request->all());

        return response()->json([
            'message' => 'Branch updated successfully',
            'branch'  => $branch,
        ]);
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully']);
    }
}