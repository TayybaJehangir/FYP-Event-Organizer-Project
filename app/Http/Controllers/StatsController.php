<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Business;


class StatsController extends Controller
{
    public function getStats()
    {
        $userStats = [
            'user_count' => User::where('role', 2)->count(),
            'vendor_count' => User::where('role', 3)->count(),
            'manager_count' => User::where('role', 4)->count(),
        ];

        $quotationStats = [
            'pending_count' => Quotation::where('status', 'Pending')->count(),
            'resolved_count' => Quotation::where('status', 'Resolved')->count(),
            'not_satisfied_count' => Quotation::where('status', 'Not Satisfied')->count(),
        ];

        $businessCount = Business::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $userStats,
                'quotations' => $quotationStats,
                'total_businesses' => $businessCount
            ]
        ]);
    }
}
