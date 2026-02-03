<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(ProductDetails::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function image(): Attribute
    {
        $url = asset('default.webp');
        if ($this->media && $this->media->src) {
            $url = Storage::url($this->media->src);
        }
        return Attribute::make(
            get: fn() => $url,
        );
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if ($category->isDirty('slug') || empty($category->slug)) {
                $source = empty($category->slug) ? $category->name : $category->slug;
                $baseSlug = Str::slug($source);

                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)
                    ->where('id', '!=', $category->id ?? 0)
                    ->exists()
                ) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $category->slug = $slug;
            }
        });
    }
}