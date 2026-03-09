<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\User;

class SprintPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Sprint $sprint): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Sprint $sprint): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Sprint $sprint): bool
    {
        return $user->isAdmin();
    }
}
