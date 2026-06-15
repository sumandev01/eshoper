<?php

namespace App\Enums\Permission;

enum BrandPermission:string
{
    case VIEW = 'brand_view';
    case CREATE = 'brand_create';
    case UPDATE = 'brand_update';
    case DELETE = 'brand_delete';
}
