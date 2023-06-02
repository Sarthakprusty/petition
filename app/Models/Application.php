<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model
{
    use HasFactory;

    protected $casts = [
        'letter_date' => 'date:Y-m-d',
    ];

    protected $fillable =[
        'file_path'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($application) {
            $application->updatePreviousStatuses();
        });
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function grievance_category(): BelongsTo
    {
        return $this->belongsTo(Grievance::class,'grievance_category_id','id');
    }

    public function department_org(): BelongsTo
    {
        return $this->belongsTo(Organization::class,'department_org_id','id');
    }
    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(Status::class)
            //->wherePivot('active','=','1')
            ->withPivot(['remarks','created_at','created_by'])
            ->withTimestamps();
    }

    public function updatePreviousStatuses()
    {
        $this->statuses()
            ->wherePivot('active', 1)
            ->updateExistingPivot($this->statuses()->pluck('application_status.id'), ['active' => 0]);
    }
}
