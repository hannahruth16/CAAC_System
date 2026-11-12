<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommender extends Model
{
    protected $primaryKey = 'recommender_id';
    public $timestamps = false;

    protected $fillable = [
        'registration_id', 'last_name', 'first_name', 'middle_name',
        'year_graduated',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'registration_id');
    }
}