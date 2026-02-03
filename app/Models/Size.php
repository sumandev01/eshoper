<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Product::class, 'product_inventories', 'size_id', 'product_id')->distinct();
    }
}
