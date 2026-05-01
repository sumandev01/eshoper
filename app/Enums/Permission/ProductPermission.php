<?php

namespace App\Enums\Permission;

enum ProductPermission:string
{
    case CREATE = 'create_product';
    case VIEW = 'view_product';
    case UPDATE = 'update_product';
    case DELETE = 'delete_product';
}
