<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(subCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
