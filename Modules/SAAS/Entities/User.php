<?php

namespace Modules\SAAS\Entities;

use App\Models\User as ModelsUser;
use Modules\SAAS\Database\factories\UserFactory;

class User extends ModelsUser
{
    protected $guard_name = 'web';

    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        $customerRole = Role::where('name', 'Customer')->first();
        static::creating(function (ModelsUser $user) use ($customerRole) {
            if ($user->roles->first() === null) {
                $user->assignRole($customerRole);
            }
        });
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function role()
    {
        return $this->roles->first()->name;
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
