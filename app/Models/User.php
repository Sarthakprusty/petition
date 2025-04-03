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
use Illuminate\Support\Facades\DB;


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
        return $this->hasMany(Application::class, 'received_by', 'id');
    }
    public function authority(): HasOne
    {
        return $this->hasone(SignAuthority::class, 'id', 'sign_id');
    }

    // public static function getUsersWithCountsForOrg174(): array
    // {
    //     return self::with('organizations', 'applications')
    //         ->where('id', '!=', 2)
    //         ->where('id', '!=', 3)
    //         ->where('active', '!=', 0)
    //         ->whereHas('organizations', function ($query) {
    //             $query->where('org_id', 174);
    //         })
    //         ->get()
    //         ->map(function ($user) {
    //             return [
    //                 'id'=> $user->id,
    //                 'name' => $user->username,
    //                 'employee_name' => $user->employee_name,
    //                 'today_count' => $user->applications->where('created_at', '>=', now()->startOfDay())->count(),
    //                 'weekly_count' => $user->applications->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
    //                 'monthly_count' => $user->applications->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
    //                 'previous_month_count' => $user->applications->whereBetween('created_at', [now()->subMonth()->startOfMonth(),now()->subMonth()->endOfMonth()])->count(),
    //                 'lifetime_count' => $user->applications->count(),
    //                 'draft' => $user->applications()->whereHas('statuses', function ($query) {
    //                     $query->where('status_id', 0)
    //                         ->where('application_status.active', 1);
    //                 })->count(),
    //                 'pending_dh' => $user->applications()->whereHas('statuses', function ($query) {
    //                     $query->where('status_id', 1)
    //                         ->where('application_status.active', 1);
    //                 })->count(),
    //             ];
    //         })
    //         ->toArray();
    // }



    // public static function getUsersWithCountsForOrg175(): array
    // {
    //     return self::with('organizations', 'applications')
    //         ->where('id', '!=', 16)
    //         ->where('id', '!=', 3)
    //         ->where('active', '!=', 0)
    //         ->whereHas('organizations', function ($query) {
    //             $query->where('org_id', 175);
    //         })
    //         ->get()
    //         ->map(function ($user) {
    //             return [
    //                 'id'=> $user->id,
    //                 'name' => $user->username,
    //                 'employee_name' => $user->employee_name,
    //                 'today_count' => $user->applications->where('created_at', '>=', now()->startOfDay())->count(),
    //                 'weekly_count' => $user->applications->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
    //                 'monthly_count' => $user->applications->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
    //                 'previous_month_count' => $user->applications->whereBetween('created_at', [now()->subMonth()->startOfMonth(),now()->subMonth()->endOfMonth()])->count(),
    //                 'lifetime_count' => $user->applications->count(),
    //                 'draft' => $user->applications()->whereHas('statuses', function ($query) {
    //                     $query->where('status_id', 0)
    //                         ->where('application_status.active', 1);
    //                 })->count(),
    //                 'pending_dh' => $user->applications()->whereHas('statuses', function ($query) {
    //                     $query->where('status_id', 1)
    //                         ->where('application_status.active', 1);
    //                 })->count(),
    //             ];
    //         })
    //         ->toArray();
    // }



    public static function getUsersWithCountsForOrg174(): array
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username as name',
                'users.employee_name',
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at >= CURDATE() THEN applications.id END) as today_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY THEN applications.id END) as weekly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) THEN applications.id END) as monthly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, "%Y-%m-01") AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH) THEN applications.id END) as previous_month_count'),
                DB::raw('COUNT(DISTINCT applications.id) as lifetime_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 0 AND application_status.active = 1 THEN applications.id END) as draft'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 1 AND application_status.active = 1 THEN applications.id END) as pending_dh')
            )
            ->leftJoin('user_organization', 'users.id', '=', 'user_organization.user_id')
            ->leftJoin('applications', 'users.id', '=', 'applications.received_by')
            ->leftJoin('application_status', 'applications.id', '=', 'application_status.application_id')
            ->where('users.active', '!=', 0)
            ->where('users.id', '!=', 2)
            ->where('users.id', '!=', 3)
            ->where('user_organization.org_id', 174)
            ->groupBy('users.id', 'users.username', 'users.employee_name')
            ->get()
            ->toArray();
    }



    public static function getUsersWithCountsForOrg175(): array
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username as name',
                'users.employee_name',
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at >= CURDATE() THEN applications.id END) as today_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY THEN applications.id END) as weekly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) THEN applications.id END) as monthly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, "%Y-%m-01") AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH) THEN applications.id END) as previous_month_count'),
                DB::raw('COUNT(DISTINCT applications.id) as lifetime_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 0 AND application_status.active = 1 THEN applications.id END) as draft'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 1 AND application_status.active = 1 THEN applications.id END) as pending_dh')
            )
            ->leftJoin('user_organization', 'users.id', '=', 'user_organization.user_id')
            ->leftJoin('applications', 'users.id', '=', 'applications.received_by')
            ->leftJoin('application_status', 'applications.id', '=', 'application_status.application_id')
            ->where('users.active', '!=', 0)
            ->where('users.id', '!=', 16)
            ->where('users.id', '!=', 3)
            ->where('user_organization.org_id', 175)
            ->groupBy('users.id', 'users.username', 'users.employee_name')
            ->get()
            ->toArray();
    }


    public static function getUsersWithCounts(): array
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.username as name',
                'users.employee_name',
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at >= CURDATE() THEN applications.id END) as today_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY THEN applications.id END) as weekly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) THEN applications.id END) as monthly_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN applications.created_at BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, "%Y-%m-01") AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH) THEN applications.id END) as previous_month_count'),
                DB::raw('COUNT(DISTINCT applications.id) as lifetime_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 0 AND application_status.active = 1 THEN applications.id END) as draft'),
                DB::raw('COUNT(DISTINCT CASE WHEN application_status.status_id = 1 AND application_status.active = 1 THEN applications.id END) as pending_dh')
            )
            ->leftJoin('user_organization', 'users.id', '=', 'user_organization.user_id')
            ->leftJoin('applications', 'users.id', '=', 'applications.received_by')
            ->leftJoin('application_status', 'applications.id', '=', 'application_status.application_id')
            ->where('users.active', '!=', 0)
            ->where('users.id', '!=', 16)
            ->where('users.id', '!=', 2)
            ->where('users.id', '!=', 3)
            ->whereIn('user_organization.org_id', [175,174])
            ->groupBy('users.id', 'users.username', 'users.employee_name')
            ->get()
            ->toArray();
    }
}



