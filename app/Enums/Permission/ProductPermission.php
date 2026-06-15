<?php

namespace App\Enums\Permission;

enum ProductPermission:string
{
    case VIEW = 'product_view';
    case CREATE = 'product_create';
    case UPDATE = 'product_update';
    case DELETE = 'product_delete';
}
