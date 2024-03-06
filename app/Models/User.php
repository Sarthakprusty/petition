<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'username_verified_at' => 'datetime',
    ];


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'user_organization', 'user_id', 'org_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'created_by', 'id');
    }
    public function authority(): HasOne
    {
        return $this->hasone(SignAuthority::class, 'id', 'sign_id');
    }

    public static function getUsersWithCountsForOrg174(): array
    {
        return self::with('organizations', 'applications')
            ->where('id', '!=', 2)
            ->where('id', '!=', 3)
            ->whereHas('organizations', function ($query) {
                $query->where('org_id', 174);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id'=> $user->id,
                    'name' => $user->username,
                    'today_count' => $user->applications->where('created_at', '>=', now()->startOfDay())->count(),
                    'weekly_count' => $user->applications->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                    'lifetime_count' => $user->applications->count(),
                    'draft' => $user->applications()->whereHas('statuses', function ($query) {
                        $query->where('status_id', 0)
                            ->where('application_status.active', 1);
                    })->count(),
                    'pending_dh' => $user->applications()->whereHas('statuses', function ($query) {
                        $query->where('status_id', 1)
                            ->where('application_status.active', 1);
                    })->count(),
                ];
            })
            ->toArray();
    }



    public static function getUsersWithCountsForOrg175(): array
    {
        return self::with('organizations', 'applications')
            ->where('id', '!=', 16)
            ->where('id', '!=', 3)
            ->whereHas('organizations', function ($query) {
                $query->where('org_id', 175);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id'=> $user->id,
                    'name' => $user->username,
                    'today_count' => $user->applications->where('created_at', '>=', now()->startOfDay())->count(),
                    'weekly_count' => $user->applications->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                    'lifetime_count' => $user->applications->count(),
                    'draft' => $user->applications()->whereHas('statuses', function ($query) {
                        $query->where('status_id', 0)
                            ->where('application_status.active', 1);
                    })->count(),
                    'pending_dh' => $user->applications()->whereHas('statuses', function ($query) {
                        $query->where('status_id', 1)
                            ->where('application_status.active', 1);
                    })->count(),
                ];
            })
            ->toArray();
    }
}


