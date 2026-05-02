<?php

namespace App\Enums\Permission;

enum BrandPermission:string
{
    case VIEW = 'view_brand';
    case CREATE = 'create_brand';
    case UPDATE = 'update_brand';
    case DELETE = 'delete_brand';
}
