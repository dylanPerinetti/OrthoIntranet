<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_SECRETAIRE = 'secretaire';
    const ROLE_ASSISTANTE = 'assistante';

    const ROLES = [
        self::ROLE_ADMIN => 'Admin / Dr André',
        self::ROLE_SECRETAIRE => 'Secrétaire',
        self::ROLE_ASSISTANTE => 'Assistante',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ── Helpers rôles ── */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSecretaire(): bool
    {
        return $this->role === self::ROLE_SECRETAIRE;
    }

    public function isAssistante(): bool
    {
        return $this->role === self::ROLE_ASSISTANTE;
    }

    public function roleName(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /* ── Relations ── */

    public function assignedStories(): HasMany
    {
        return $this->hasMany(UserStory::class, 'assigned_to');
    }

    public function createdStories(): HasMany
    {
        return $this->hasMany(UserStory::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
