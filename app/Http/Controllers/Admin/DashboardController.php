<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Motorcycle;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_owners'    => User::where('role', 'owner')->count(),
            'total_drivers'   => User::where('role', 'driver')->count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'total_disbursed' => Contract::sum('financed_amount'),
            'total_collected' => Payment::where('status', 'confirmed')->sum('amount'),
            'defaulted'       => Contract::where('status', 'defaulted')->count(),
            'total_motorcycles' => Motorcycle::count(),
        ];

        $stats['total_outstanding'] = bcsub(
            (string)$stats['total_disbursed'],
            (string)$stats['total_collected'],
            2
        );

        $recentPayments = Payment::with(['contract.motorcycle', 'driver'])
            ->where('status', 'confirmed')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPayments'));
    }
}
