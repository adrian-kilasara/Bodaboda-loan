<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    protected $fillable = [
        'user_id',
        'national_id',
        'driving_license_number',
        'license_expiry',
        'physical_address',
        'city',
        'region',
        'photo_path',
    ];

    protected function casts(): array
    {
        return ['license_expiry' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
