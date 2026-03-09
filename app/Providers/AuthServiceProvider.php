<?php

namespace App\Providers;

use App\Models\Persona;
use App\Models\Sprint;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use App\Policies\PersonaPolicy;
use App\Policies\SprintPolicy;
use App\Policies\StreamPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserStoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        UserStory::class => UserStoryPolicy::class,
        Persona::class => PersonaPolicy::class,
        Sprint::class => SprintPolicy::class,
        Stream::class => StreamPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-audit-logs', fn (User $user) => $user->isAdmin());
    }
}
