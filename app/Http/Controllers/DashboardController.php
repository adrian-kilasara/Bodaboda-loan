<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Motorcycle;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function owner()
    {
        $user = auth()->user();

        $stats = [
            'motorcycles'      => Motorcycle::where('owner_id', $user->id)->count(),
            'active_contracts' => Contract::where('owner_id', $user->id)->where('status', 'active')->count(),
            'total_outstanding' => Contract::where('owner_id', $user->id)
                ->whereIn('status', ['active', 'defaulted'])
                ->with('installments')
                ->get()
                ->sum(fn($c) => (float) $c->balanceRemaining()),
            'collected_this_week' => Payment::whereHas('contract', fn($q) => $q->where('owner_id', $user->id))
                ->where('status', 'confirmed')
                ->where('payment_date', '>=', now()->startOfWeek())
                ->sum('amount'),
            'overdue_count' => Contract::where('owner_id', $user->id)
                ->where('status', 'active')
                ->get()
                ->filter(fn($c) => !$c->isOnTrack())
                ->count(),
        ];

        $behindContracts = Contract::where('owner_id', $user->id)
            ->whereIn('status', ['active', 'defaulted'])
            ->with(['motorcycle', 'driver'])
            ->get()
            ->filter(fn($c) => !$c->isOnTrack())
            ->take(10);

        return view('owner.dashboard', compact('stats', 'behindContracts'));
    }

    public function driver()
    {
        $user = auth()->user();
        $contract = Contract::where('driver_id', $user->id)
            ->whereIn('status', ['active', 'completed', 'defaulted'])
            ->with(['motorcycle', 'owner', 'installments', 'payments' => fn($q) => $q->where('status', 'confirmed')->latest('payment_date')])
            ->latest()
            ->first();

        return view('driver.dashboard', compact('contract'));
    }
}
