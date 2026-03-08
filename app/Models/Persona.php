<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role', 'description',
        'goals', 'pain_points', 'needs',
        'avatar_color', 'sort_order',
    ];

    public function userStories(): HasMany
    {
        return $this->hasMany(UserStory::class);
    }
}
