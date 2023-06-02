<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Status extends Model
{
    use HasFactory;
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class)
            ->wherePivot('active','=','1')
            ->withPivot(['remarks','created_at','created_by'])
            ->withTimestamps();
    }
}
