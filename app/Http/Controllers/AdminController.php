<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Super Admin — all bookings
    public function allBookings()
    {
        $bookings = Booking::with(['user', 'route', 'bus', 'seat', 'branch'])->get();
        return response()->json($bookings);
    }

    // Super Admin — all sub-admins
    public function allSubAdmins()
    {
        $subAdmins = User::where('role', 'sub_admin')->with('branch')->get();
        return response()->json($subAdmins);
    }

    // Super Admin — create sub-admin
    public function createSubAdmin(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'sub_admin',
            'branch_id' => $request->branch_id,
        ]);

        return response()->json([
            'message' => 'Sub-admin created successfully',
            'user'    => $user,
        ], 201);
    }

    // Sub Admin — branch bookings only
    public function branchBookings(Request $request)
    {
        $bookings = Booking::with(['user', 'route', 'bus', 'seat'])
            ->where('branch_id', $request->user()->branch_id)
            ->get();

        return response()->json($bookings);
    }

    // Global dashboard stats
    public function stats()
    {
        return response()->json([
            'total_bookings' => Booking::count(),
            'total_users'    => User::where('role', 'customer')->count(),
            'total_branches' => Branch::count(),
            'total_revenue'  => Booking::where('status', 'confirmed')->sum('amount_paid'),
        ]);
    }
}