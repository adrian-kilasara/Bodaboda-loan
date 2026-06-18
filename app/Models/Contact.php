<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'driver_id',
        'contract_id',
        'full_name',
        'phone',
        'alternate_phone',
        'relationship',
        'national_id',
        'physical_address',
        'notes',
    ];

    public function owner()    { return $this->belongsTo(User::class, 'owner_id'); }
    public function driver()   { return $this->belongsTo(User::class, 'driver_id'); }
    public function contract() { return $this->belongsTo(Contract::class); }

    public function relationshipLabel(): string
    {
        return match($this->relationship) {
            'guarantor'   => 'Guarantor',
            'next_of_kin' => 'Next of Kin',
            'reference'   => 'Reference',
            default       => 'Other',
        };
    }
}
