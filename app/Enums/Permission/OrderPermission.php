<?php

namespace App\Enums\Permission;

enum OrderPermission:string
{
    case VIEW = 'view_order';
    case UPDATE = 'update_order';
    case DELETE = 'delete_order';
}
