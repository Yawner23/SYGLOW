<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $fillable = ['name'];

    // Relationship: A permission can belong to many roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
