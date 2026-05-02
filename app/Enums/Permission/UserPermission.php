<?php

namespace App\Enums\Permission;

enum UserPermission:string
{
    case VIEW = 'view_user';
    case CREATE = 'create_user';
    case UPDATE = 'update_user';
    case DELETE = 'delete_user';
}
