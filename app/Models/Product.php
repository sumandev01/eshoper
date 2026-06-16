<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = ['id'];

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Surgical eager loading for product listings.
     * Prevents memory bloat and N+1 issues by selecting specific columns.
     */
    public function scopeWithListingDefaults($query)
    {
        return $query->select(['id', 'name', 'slug', 'price', 'discount', 'media_id', 'status'])
            ->with([
                'media:id,src',
                'details:id,product_id,category_id,sub_category_id,brand_id',
                'inventories' => function ($q) {
                    $q->select(['id', 'product_id', 'size_id', 'color_id', 'price', 'discount', 'stock', 'media_id', 'use_main_price', 'use_main_discount'])
                        ->with(['color:id,name', 'size:id,name', 'media:id,src']);
                }
            ]);
    }

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

    public function formattedVariants(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->inventories->map(function ($inventory) {
                return [
                    'id' => $inventory->id,
                    'size_id' => $inventory->size_id,
                    'color_id' => $inventory->color_id,
                    'color_name' => $inventory->color->name ?? 'N/A',
                    'stock' => $inventory->stock,
                    'price' => $inventory->use_main_price == 1 ? $this->price : $inventory->price,
                    'discount_price' => $inventory->use_main_discount == 1 ? $this->discount : $inventory->discount,
                    'image' => $inventory->media ? $inventory->media->medium_url : null,
                ];
            }),
        );
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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
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
                while (self::whereSlug($slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $product->slug = $slug;
            }
        });
    }
}
