<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
    public function organizations(): HasMany
    {
        return $this->hasMany(Application::class, 'state_id', 'id');
    }

}
