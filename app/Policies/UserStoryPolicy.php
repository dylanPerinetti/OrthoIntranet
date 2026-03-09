<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserStory;

class UserStoryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE, User::ROLE_ASSISTANTE], true);
    }

    public function view(User $user, UserStory $userStory): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE], true);
    }

    public function update(User $user, UserStory $userStory): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE], true);
    }

    public function delete(User $user, UserStory $userStory): bool
    {
        return $user->isAdmin();
    }

    public function move(User $user, UserStory $userStory): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_SECRETAIRE], true);
    }
}
