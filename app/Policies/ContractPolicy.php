<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function view(User $user, Contract $contract): bool
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'owner') return $contract->owner_id === $user->id;
        if ($user->role === 'driver') return $contract->driver_id === $user->id;
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    public function update(User $user, Contract $contract): bool
    {
        return $user->role === 'admin' || $contract->owner_id === $user->id;
    }

    public function recordPayment(User $user, Contract $contract): bool
    {
        return $user->role === 'admin' || $contract->owner_id === $user->id;
    }

    public function generateKey(User $user, Contract $contract): bool
    {
        return $user->role === 'admin' || $contract->owner_id === $user->id;
    }
}
