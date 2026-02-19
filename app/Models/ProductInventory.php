<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductInventory extends Model
{
    protected $guarded = ['id'];

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function thumbnail():Attribute
    {
        $url = asset('default.webp');
        if ($this->media && $this->media->src) {
            $url = Storage::url($this->media->src);
        }
        return Attribute::make(
            get: fn() => $url,
        );
    }
}
