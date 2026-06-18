<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'contract_id',
        'installment_number',
        'due_date',
        'amount_due',
        'amount_paid',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_due'  => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'due_date'    => 'date',
            'paid_at'     => 'datetime',
        ];
    }

    public function contract()  { return $this->belongsTo(Contract::class); }
    public function allocations() { return $this->hasMany(PaymentAllocation::class); }

    public function amountRemaining(): string
    {
        return bcsub((string)$this->amount_due, (string)$this->amount_paid, 2);
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, ['pending', 'partial', 'overdue'])
            && $this->due_date->isPast();
    }
}
