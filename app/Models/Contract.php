<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'motorcycle_id',
        'owner_id',
        'driver_id',
        'enrolment_key',
        'enrolment_key_expires_at',
        'principal_amount',
        'markup_amount',
        'interest_rate',
        'total_payable',
        'down_payment',
        'financed_amount',
        'installment_amount',
        'installment_frequency',
        'number_of_installments',
        'penalty_type',
        'penalty_amount',
        'grace_period_days',
        'start_date',
        'expected_end_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'principal_amount'       => 'decimal:2',
            'markup_amount'          => 'decimal:2',
            'interest_rate'          => 'decimal:2',
            'total_payable'          => 'decimal:2',
            'down_payment'           => 'decimal:2',
            'financed_amount'        => 'decimal:2',
            'installment_amount'     => 'decimal:2',
            'penalty_amount'         => 'decimal:2',
            'start_date'             => 'date',
            'expected_end_date'      => 'date',
            'enrolment_key_expires_at' => 'datetime',
        ];
    }

    public function motorcycle() { return $this->belongsTo(Motorcycle::class); }
    public function owner()      { return $this->belongsTo(User::class, 'owner_id'); }
    public function driver()     { return $this->belongsTo(User::class, 'driver_id'); }
    public function installments() { return $this->hasMany(Installment::class)->orderBy('installment_number'); }
    public function payments()     { return $this->hasMany(Payment::class)->orderByDesc('payment_date'); }
    public function contacts()     { return $this->hasMany(Contact::class); }

    public function confirmedPayments()
    {
        return $this->hasMany(Payment::class)->where('status', 'confirmed');
    }

    public function amountPaidTotal(): string
    {
        return $this->confirmedPayments()->sum('amount');
    }

    public function balanceRemaining(): string
    {
        return bcsub((string)$this->financed_amount, (string)$this->amountPaidTotal(), 2);
    }

    public function overdueAmount(): string
    {
        return $this->installments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->where('due_date', '<', now()->toDateString())
            ->selectRaw('SUM(amount_due - amount_paid) as total')
            ->value('total') ?? '0.00';
    }

    public function isOnTrack(): bool
    {
        return bccomp((string)$this->overdueAmount(), '0', 2) === 0;
    }

    public function nextDueInstallment()
    {
        return $this->installments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('due_date')
            ->first();
    }

    public function percentPaid(): float
    {
        if (bccomp((string)$this->financed_amount, '0', 2) === 0) return 0;
        return min(100, round(((float)$this->amountPaidTotal() / (float)$this->financed_amount) * 100, 1));
    }

    public function hasValidEnrolmentKey(): bool
    {
        return $this->enrolment_key !== null
            && $this->enrolment_key_expires_at
            && $this->enrolment_key_expires_at->isFuture()
            && $this->driver_id === null;
    }
}
