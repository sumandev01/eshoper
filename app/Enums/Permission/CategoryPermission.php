<?php

namespace App\Enums\Permission;

enum CategoryPermission:string
{
    case VIEW = 'view_category';
    case CREATE = 'create_category';
    case UPDATE = 'update_category';
    case DELETE = 'delete_category';
}
