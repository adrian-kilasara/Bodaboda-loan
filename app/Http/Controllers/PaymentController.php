<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Services\PaymentAllocator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PaymentAllocator $allocator) {}

    public function store(StorePaymentRequest $request, Contract $contract)
    {
        $this->authorize('recordPayment', $contract);

        if (!in_array($contract->status, ['active', 'defaulted'])) {
            return back()->with('error', 'Payments can only be recorded on active contracts.');
        }

        $payment = Payment::create([
            'payment_reference'  => 'PAY-' . strtoupper(Str::random(8)),
            'contract_id'        => $contract->id,
            'driver_id'          => $contract->driver_id,
            'amount'             => $request->amount,
            'payment_date'       => $request->payment_date,
            'channel'            => $request->channel,
            'external_reference' => $request->external_reference,
            'recorded_by'        => auth()->id(),
            'confirmed_by'       => auth()->id(),
            'confirmed_at'       => now(),
            'status'             => 'confirmed',
            'notes'              => $request->notes,
        ]);

        $this->allocator->allocate($payment);

        return back()->with('success', 'Payment of TZS ' . number_format($request->amount) . ' recorded and allocated.');
    }
}
