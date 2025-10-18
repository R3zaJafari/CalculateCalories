<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    protected $fillable = [
        'user_id',
        'meal_name',
        'calories',
    ];

    // Each meal belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
