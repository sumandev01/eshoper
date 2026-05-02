<?php

namespace App\Enums\Permission;

enum SizePermission:string
{
    case VIEW = 'view_size';
    case CREATE = 'create_size';
    case UPDATE = 'update_size';
    case DELETE = 'delete_size';
}
