<?php

namespace App\Enums;

enum LinkTypeEnum: string
{
    case SYSTEM = 'system';
    case CATEGORY = 'category';
    case PRODUCT = 'product';
    case PAGE = 'page';
    case BLOG = 'blog';
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::SYSTEM => 'System Page',
            self::CATEGORY => 'Category',
            self::PRODUCT => 'Product',
            self::PAGE => 'Dynamic Page',
            self::BLOG => 'Blog Post',
            self::CUSTOM => 'Custom URL',
        };
    }
}
