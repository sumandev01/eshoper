<?php

namespace App\Enums\Permission;

enum ColorPermission:string
{
    case VIEW = 'color_view';
    case CREATE = 'color_create';
    case UPDATE = 'color_update';
    case DELETE = 'color_delete';
}
