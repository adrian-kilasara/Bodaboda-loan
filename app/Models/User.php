<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isDriver(): bool { return $this->role === 'driver'; }
    public function isActive(): bool { return $this->status === 'active'; }

    public function ownerProfile()
    {
        return $this->hasOne(OwnerProfile::class);
    }

    public function driverProfile()
    {
        return $this->hasOne(DriverProfile::class);
    }

    public function motorcycles()
    {
        return $this->hasMany(Motorcycle::class, 'owner_id');
    }

    public function ownedContracts()
    {
        return $this->hasMany(Contract::class, 'owner_id');
    }

    public function driverContracts()
    {
        return $this->hasMany(Contract::class, 'driver_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'driver_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'owner_id');
    }
}
