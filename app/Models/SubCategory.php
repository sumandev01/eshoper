<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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

        // 'saving' works for both Creating and Updating
        static::saving(function ($subCategory) {
            if ($subCategory->isDirty('slug') || empty($subCategory->slug)) {
                // Use provided slug or fallback to name
                $source = empty($subCategory->slug) ? $subCategory->name : $subCategory->slug;
                $baseSlug = Str::slug($source);

                $slug = $baseSlug;
                $count = 1;

                // Ensure uniqueness, but ignore current record ID during update
                while (static::where('slug', $slug)
                    ->where('id', '!=', $subCategory->id ?? 0)
                    ->exists()
                ) {
                    $slug = $baseSlug . '-' . $count++;
                }

                $subCategory->slug = $slug;
            }
        });
    }
}
