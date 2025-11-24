<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship: A user can have multiple roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'role_user', 'permission_role', 'id', 'id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }
    public function socialMediaAccounts()
    {
        return $this->hasOne(SocialMediaAccounts::class, 'user_id');
    }
    public function distributor()
    {
        return $this->hasOne(Distributor::class, 'user_id');
    }
    public function delivery_address()
    {
        return $this->hasMany(DeliveryAddress::class, 'customer_id', 'id');
    }
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role->name)->exists();
    }


    public function hasPermission($permission)
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }
}
