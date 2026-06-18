<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'owner') return $payment->contract->owner_id === $user->id;
        if ($user->role === 'driver') return $payment->driver_id === $user->id;
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }
}
