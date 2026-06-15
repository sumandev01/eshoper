<?php

namespace App\Enums\Permission;

enum SizePermission:string
{
    case VIEW = 'size_view';
    case CREATE = 'size_create';
    case UPDATE = 'size_update';
    case DELETE = 'size_delete';
}
