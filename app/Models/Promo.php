<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['code', 'discount_type', 'discount_value', 'max_uses', 'uses', 'valid_until', 'is_active'];

    protected $casts = [
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];
}
