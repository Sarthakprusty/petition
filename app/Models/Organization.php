<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;
    public function applications()
    {
        return $this->hasManyThrough(Application::class, User::class, 'organization_id', 'created_by');
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'organization_roles', 'org_id', 'role_id');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_organization', 'org_id', 'user_id');
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'id', 'state_id');
    }

}
