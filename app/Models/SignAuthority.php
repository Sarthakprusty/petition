<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SignAuthority extends Model
{
    use HasFactory;
    protected $casts = [
        'form_date' => 'date:Y-m-d',
        'to_date' => 'date:Y-m-d',

    ];

    protected $fillable =[
        'signature_path'
    ];

    public function applications(): HasMany
    {
        return $this->hasmany(Application::class);
    }
    public function user(): HasOne
    {
        return $this->hasone(User::class,'created_by','id');
    }
}
