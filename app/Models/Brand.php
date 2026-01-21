<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class);
    }

    public function thumbnail(): Attribute
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
        static::saving(function ($brand) {
            if ($brand->isDirty('slug') || empty($brand->slug)) {
                $source = empty($brand->slug) ? $brand->name : $brand->slug;
                $baseSlug = Str::slug($source);

                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)
                    ->where('id', '!=', $brand->id ?? 0)
                    ->exists()
                ) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $brand->slug = $slug;
            }
        });
    }
}
