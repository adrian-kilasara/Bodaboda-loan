<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'contract_id',
        'driver_id',
        'amount',
        'payment_date',
        'channel',
        'external_reference',
        'recorded_by',
        'status',
        'confirmed_by',
        'confirmed_at',
        'rejection_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'payment_date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    public function contract()    { return $this->belongsTo(Contract::class); }
    public function driver()      { return $this->belongsTo(User::class, 'driver_id'); }
    public function recorder()    { return $this->belongsTo(User::class, 'recorded_by'); }
    public function confirmer()   { return $this->belongsTo(User::class, 'confirmed_by'); }
    public function allocations() { return $this->hasMany(PaymentAllocation::class); }

    public function channelLabel(): string
    {
        return match($this->channel) {
            'mpesa'    => 'M-Pesa',
            'tigopesa' => 'Tigo Pesa',
            'airtel'   => 'Airtel Money',
            'halopesa' => 'Halo Pesa',
            'bank'     => 'Bank Transfer',
            'cash'     => 'Cash',
            default    => 'Other',
        };
    }
}
