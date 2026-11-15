<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'username',
        'email',
        'password',
        'account_status',
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

    // Tell Laravel's authentication system that the stored password column is password_hash.
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function registration()
    {
        return $this->hasOne(Registration::class, 'user_id', 'user_id');
    }

    public function reviewedRegistrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'reviewed_by', 'user_id');
    }

    public function uploadedDocuments(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'uploaded_by', 'user_id');
    }

    public function verifiedDocuments(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'verified_by', 'user_id');
    }

    public function registrationReviews(): HasMany
    {
        return $this->hasMany(RegistrationReview::class, 'reviewer_id', 'user_id');
    }
}
