<?php

namespace App\Policies;

use App\Models\Stream;
use App\Models\User;

class StreamPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Stream $stream): bool
    {
        return true;
    }
}
