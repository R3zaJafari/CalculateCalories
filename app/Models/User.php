<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
    ];

    protected $casts = [
        'telegram_id' => 'string',
    ];

    // Each user has many meals
    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }
}
