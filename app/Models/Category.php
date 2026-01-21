<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function galleries()
    {
        return $this->belongsToMany(Media::class, 'category_galleries', 'category_id', 'media_id');
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

    protected function galleryUrls(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Check if galleries relation is loaded and not empty
                if ($this->galleries->isNotEmpty()) {
                    return $this->galleries->map(function ($media) {
                        return $media->src ? Storage::url($media->src) : asset('default.webp');
                    });
                }
                // Return empty collection or default image in array if no gallery exists
                return [];
            },
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