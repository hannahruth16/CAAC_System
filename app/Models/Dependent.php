<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dependent extends Model
{
    protected $primaryKey = 'dependent_id';
    public $timestamps = false;

    protected $fillable = [
        'registration_id', 'full_name', 'relationship', 'date_of_birth', 'remarks',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
    }
}