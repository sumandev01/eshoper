<?php

namespace App\Enums\Permission;

enum CategoryPermission:string
{
    case VIEW = 'category_view';
    case CREATE = 'category_create';
    case UPDATE = 'category_update';
    case DELETE = 'category_delete';
}
