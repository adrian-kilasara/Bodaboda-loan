<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    protected $fillable = [
        'payment_id',
        'installment_id',
        'amount_applied',
    ];

    protected function casts(): array
    {
        return ['amount_applied' => 'decimal:2'];
    }

    public function payment()     { return $this->belongsTo(Payment::class); }
    public function installment() { return $this->belongsTo(Installment::class); }
}
