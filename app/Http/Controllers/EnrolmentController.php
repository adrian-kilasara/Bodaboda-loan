<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EnrolmentController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $hasContract = Contract::where('driver_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->exists();

        return view('driver.enrol', compact('hasContract'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrolment_key' => ['required', 'string'],
        ]);

        $key = strtoupper(trim($request->enrolment_key));

        $contract = Contract::where('status', 'pending_enrolment')
            ->whereNull('driver_id')
            ->where('enrolment_key_expires_at', '>', now())
            ->get()
            ->first(fn($c) => Hash::check($key, $c->enrolment_key));

        if (!$contract) {
            return back()->withErrors(['enrolment_key' => 'Key is invalid, expired, or already used.']);
        }

        $contract->update([
            'driver_id'     => auth()->id(),
            'enrolment_key' => null,
            'enrolment_key_expires_at' => null,
            'status'        => 'active',
        ]);

        return redirect()->route('driver.dashboard')
            ->with('success', 'You have been linked to contract ' . $contract->contract_number . '. Welcome!');
    }
}
