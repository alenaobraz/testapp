<?php

namespace App\Traits;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    public function hasRole (... $roles)
    {
        foreach ($roles as $role)
        {
            if ($this->roles()->contains('role', $role))
            {
                return true;
            }
        }
        return false;
    }
}