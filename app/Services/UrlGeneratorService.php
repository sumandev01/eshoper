<?php

namespace App\Services;

use App\Enums\LinkTypeEnum;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class UrlGeneratorService
{
    /**
     * Resolve the dynamic link based on type and reference ID.
     */
    public static function resolve(?string $type, ?string $refId): string
    {
        if (empty($type) || empty($refId)) {
            return '#';
        }

        $linkType = LinkTypeEnum::tryFrom($type);

        if (!$linkType) {
            return '#';
        }

        return match ($linkType) {
            LinkTypeEnum::SYSTEM => self::resolveSystemPage($refId),
            LinkTypeEnum::CATEGORY => self::resolveCategory($refId),
            LinkTypeEnum::PRODUCT => self::resolveProduct($refId),
            LinkTypeEnum::PAGE => self::resolveDynamicPage($refId),
            LinkTypeEnum::BLOG => self::resolveBlog($refId),
            LinkTypeEnum::CUSTOM => $refId,
        };
    }

    private static function resolveSystemPage(string $refId): string
    {
        return match ($refId) {
            'home' => route('root'),
            'shop' => route('shop'),
            'cart' => route('cart'),
            'orderTracking' => route('orderTracking'),
            'contact' => route('contact'),
            'about' => route('about'),
            'faq' => route('faq'),
            'blogs' => route('web.blogs.index'),
            default => url('/' . ltrim($refId, '/')),
        };
    }

    private static function resolveCategory(string $refId): string
    {
        return Cache::rememberForever("link_category_{$refId}", function () use ($refId) {
            $category = Category::find($refId);
            return $category ? route('category.products', $category->slug) : '#';
        });
    }

    private static function resolveProduct(string $refId): string
    {
        return Cache::rememberForever("link_product_{$refId}", function () use ($refId) {
            $product = Product::find($refId);
            return $product ? route('product.details', $product->slug) : '#';
        });
    }

    private static function resolvePage(string $refId): string
    {
        return Cache::rememberForever("link_page_{$refId}", function () use ($refId) {
            $page = Page::find($refId);
            return $page ? route('page', $page->slug) : '#';
        });
    }

    private static function resolveBlog(string $refId): string
    {
        return Cache::rememberForever("link_blog_{$refId}", function () use ($refId) {
            $blog = Blog::find($refId);
            return $blog ? route('web.blogs.show', $blog->slug) : '#';
        });
    }
}
