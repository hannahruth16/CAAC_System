<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $fillable = ['permission_code', 'permission_name', 'description'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'permission_id',
            'role_id',
            'permission_id',
            'role_id'
        );
    }
}