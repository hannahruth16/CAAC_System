<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    protected $primaryKey = 'registration_id';

    protected $fillable = [
        'user_id', 'registration_no', 'family_name', 'first_name',
        'middle_name', 'suffix', 'gender', 'height', 'birth_date',
        'birth_place', 'marital_status', 'citizenship', 'religion',
        'mobile_no', 'landline', 'tin_no', 'sss_gsis_no', 'email',
        'year_graduated', 'graduation_school', 'status', 'submitted_at',
        'reviewed_at', 'reviewed_by', 'remarks',
    ];

    protected $casts = [
        'height' => 'decimal:2',
        'birth_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    protected $appends = ['age', 'full_name'];

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->family_name,
            $this->suffix,
        ])));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'registration_id', 'registration_id');
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class, 'registration_id', 'registration_id');
    }

    public function recommender(): HasOne
    {
        return $this->hasOne(Recommender::class, 'registration_id', 'registration_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_id', 'registration_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(RegistrationReview::class, 'registration_id', 'registration_id');
    }
}