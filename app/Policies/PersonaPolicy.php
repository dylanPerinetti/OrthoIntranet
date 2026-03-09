<?php

namespace App\Policies;

use App\Models\Persona;
use App\Models\User;

class PersonaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Persona $persona): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE], true);
    }

    public function update(User $user, Persona $persona): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE], true);
    }

    public function delete(User $user, Persona $persona): bool
    {
        return $user->isAdmin();
    }
}
