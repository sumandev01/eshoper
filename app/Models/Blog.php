<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'content',
        'media_id',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'status',
        'views'
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function thumbnail(): Attribute
    {
        $default = asset('default.webp');
        return Attribute::make(
            get: fn () => $this->media ? $this->media->thumbnail ?? $default : $default,
        );
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }
}
