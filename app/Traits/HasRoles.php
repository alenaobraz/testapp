<?php

namespace App\Traits;
use App\Models\Role;

trait HasRoles
{
    /**
     * Отношение многие-ко-многим между пользователями и ролями
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    /**
     * Проверка, есть ли у текущего пользователя конкретная роль
     *
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(... $roles)
    {
        foreach ($roles as $role)
        {
            if ($this->roles->contains('role', $role))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Создание отношения между пользователем и ролью
     *
     * @param string
     * @return void
     */
    public function setRole ($role_name)
    {
        $role = Role::where('role',$role_name)->first();
        $this->roles()->attach($role);
    }
}
