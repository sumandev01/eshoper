<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = ['id'];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    // public function subCategory()
    // {
    //     return $this->belongsTo(SubCategory::class);
    // }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function inventories()
    {
        return $this->hasMany(ProductInventory::class)->orderBy('size_id', 'desc');
    }

    public function thumbnail(): Attribute
    {
        $default = asset('default.webp');
        return Attribute::make(
            get: fn () => $this->media ? $this->media->thumbnail ?? $default : $default,
        );
    }

    public function galleries()
    {
        return $this->belongsToMany(Media::class, 'product_galleries', 'product_id', 'media_id');
    }

    public function details()
    {
        return $this->hasOne(ProductDetails::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_inventories', 'product_id', 'color_id')->distinct();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_inventories', 'product_id', 'size_id')->distinct();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_details', 'product_id', 'category_id')->distinct();
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($product) {
            if ($product->isDirty('slug') || empty($product->slug)) {
                $source = empty($product->slug) ? $product->name : $product->slug;
                $baseSlug = Str::slug($source);

                $slug = $baseSlug;
                $count = 1;
                while (self::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $product->slug = $slug;
            }
        });
    }
}
