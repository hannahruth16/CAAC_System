<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $primaryKey = 'document_type_id';
    public $timestamps = false;

    protected $fillable = ['document_name', 'description', 'is_required', 'is_active'];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function registrationDocuments(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class, 'document_type_id', 'document_type_id');
    }
}