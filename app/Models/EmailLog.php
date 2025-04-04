<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function applications(): BelongsTo
    {
        return $this->belongsTo(Application::class,'application_id','id');
    }
}
