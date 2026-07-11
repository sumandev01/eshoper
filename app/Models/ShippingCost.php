<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    protected $guarded = ['id'];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
