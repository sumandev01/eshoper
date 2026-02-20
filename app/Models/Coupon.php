<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'expire_date' => 'datetime',
    ];
}
