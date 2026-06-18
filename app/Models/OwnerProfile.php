<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'national_id',
        'physical_address',
        'city',
        'region',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
