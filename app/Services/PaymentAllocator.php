<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Installment;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use Illuminate\Support\Facades\DB;

class PaymentAllocator
{
    public function allocate(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $contract = $payment->contract;
            $remaining = (string) $payment->amount;

            $installments = Installment::where('contract_id', $contract->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->orderBy('due_date')
                ->orderBy('installment_number')
                ->get();

            foreach ($installments as $installment) {
                if (bccomp($remaining, '0', 2) <= 0) break;

                $needed = $installment->amountRemaining();
                if (bccomp($needed, '0', 2) <= 0) continue;

                $applied = bccomp($remaining, $needed, 2) >= 0 ? $needed : $remaining;

                PaymentAllocation::create([
                    'payment_id'     => $payment->id,
                    'installment_id' => $installment->id,
                    'amount_applied' => $applied,
                ]);

                $newPaid = bcadd((string) $installment->amount_paid, $applied, 2);
                $installment->amount_paid = $newPaid;

                if (bccomp($newPaid, (string) $installment->amount_due, 2) >= 0) {
                    $installment->status  = 'paid';
                    $installment->paid_at = now();
                } else {
                    $installment->status = 'partial';
                }
                $installment->save();

                $remaining = bcsub($remaining, $applied, 2);
            }

            $totalPaid = $contract->confirmedPayments()->sum('amount');
            if (bccomp((string) $totalPaid, (string) $contract->financed_amount, 2) >= 0) {
                $contract->update(['status' => 'completed']);
                $contract->motorcycle()->update(['status' => 'available']);
            }
        });
    }
}
