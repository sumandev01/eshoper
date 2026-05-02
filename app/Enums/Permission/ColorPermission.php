<?php

namespace App\Enums\Permission;

enum ColorPermission:string
{
    case VIEW = 'view_color';
    case CREATE = 'create_color';
    case UPDATE = 'update_color';
    case DELETE = 'delete_color';
}
