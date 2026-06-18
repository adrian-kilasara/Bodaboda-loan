<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleGenerator
{
    public function generate(Contract $contract): void
    {
        DB::transaction(function () use ($contract) {
            $contract->installments()->delete();

            $n = $contract->number_of_installments;
            $base = $contract->installment_amount;
            $financed = (string) $contract->financed_amount;

            $totalBase = bcmul((string) $base, (string) $n, 2);
            $rounding = bcsub($financed, $totalBase, 2);

            $date = Carbon::parse($contract->start_date);

            for ($i = 1; $i <= $n; $i++) {
                $dueDate = $this->nextDueDate($date, $contract->installment_frequency, $i);
                $amount = ($i === $n)
                    ? bcadd((string) $base, $rounding, 2)
                    : (string) $base;

                Installment::create([
                    'contract_id'        => $contract->id,
                    'installment_number' => $i,
                    'due_date'           => $dueDate->toDateString(),
                    'amount_due'         => $amount,
                    'amount_paid'        => '0.00',
                    'status'             => 'pending',
                ]);
            }

            $lastDate = $this->nextDueDate($date, $contract->installment_frequency, $n);
            $contract->update(['expected_end_date' => $lastDate->toDateString()]);
        });
    }

    private function nextDueDate(Carbon $start, string $frequency, int $number): Carbon
    {
        return match ($frequency) {
            'daily'   => (clone $start)->addDays($number),
            'weekly'  => (clone $start)->addWeeks($number),
            'monthly' => (clone $start)->addMonths($number),
        };
    }

    public function previewSchedule(
        string $startDate,
        string $frequency,
        int $count,
        string $installmentAmount,
        string $financedAmount
    ): array {
        $base = $installmentAmount;
        $totalBase = bcmul($base, (string) $count, 2);
        $rounding = bcsub($financedAmount, $totalBase, 2);
        $date = Carbon::parse($startDate);
        $rows = [];

        for ($i = 1; $i <= $count; $i++) {
            $dueDate = $this->nextDueDate($date, $frequency, $i);
            $amount = ($i === $count) ? bcadd($base, $rounding, 2) : $base;
            $rows[] = [
                'number'   => $i,
                'due_date' => $dueDate->toDateString(),
                'amount'   => $amount,
            ];
        }

        return $rows;
    }
}
