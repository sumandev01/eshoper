<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'position',
        'title',
        'subtitle',
        'image_id',
        'link_type',
        'link_ref_id',
        'status',
    ];

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? \Illuminate\Support\Facades\Storage::url($this->image->src) : asset('default.webp');
    }

    public function getLinkAttribute()
    {
        if ($this->link_type == 'custom') {
            return $this->link_ref_id;
        } elseif ($this->link_type == 'system') {
            $routes = [
                'home' => route('root'),
                'shop' => route('shop'),
                'cart' => route('cart'),
                'orderTracking' => route('orderTracking'),
                'contact' => route('contact'),
                'about' => route('about'),
                'faq' => route('faq'),
                'blogs' => route('web.blogs.index'),
            ];
            return $routes[$this->link_ref_id] ?? '#';
        } elseif ($this->link_type == 'category') {
            $cat = \App\Models\Category::find($this->link_ref_id);
            return $cat ? route('category.products', $cat->slug) : '#';
        } elseif ($this->link_type == 'product') {
            $prod = \App\Models\Product::find($this->link_ref_id);
            return $prod ? route('product.details', $prod->slug) : '#';
        } elseif ($this->link_type == 'page') {
            $page = \App\Models\Page::find($this->link_ref_id);
            return $page ? route('page', $page->slug) : '#';
        } elseif ($this->link_type == 'blog') {
            $blog = \App\Models\Blog::find($this->link_ref_id);
            return $blog ? route('web.blogs.show', $blog->slug) : '#';
        }

        return '#';
    }
}
