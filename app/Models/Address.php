<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $primaryKey = 'address_id';
    public $timestamps = false;

    protected $fillable = [
        'registration_id', 'address_type', 'address_line', 'house_no',
        'street', 'barangay', 'municipality', 'city', 'province',
        'region', 'zip_code', 'is_primary',
    ];

    protected $casts = ['is_primary' => 'boolean'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
    }
}