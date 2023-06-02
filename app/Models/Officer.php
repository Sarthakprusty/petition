<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Officer extends Model
{
    use HasFactory;
    protected $casts = [
        'form_date' => 'date:Y-m-d',
        'to_date' => 'date:Y-m-d',

    ];

    protected $fillable =[
        'signature_path'
    ];



    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
