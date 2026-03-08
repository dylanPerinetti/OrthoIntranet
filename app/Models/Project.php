<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class)->orderBy('sort_order');
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class)->orderBy('number');
    }
}
