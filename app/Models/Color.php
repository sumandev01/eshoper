<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = ['id'];

    public function colors()
    {
        return $this->belongsToMany(Product::class, 'product_inventories', 'color_id', 'product_id')->distinct();
    }

    public function inventories()
    {
        return $this->hasMany(ProductInventory::class);
    }
}
