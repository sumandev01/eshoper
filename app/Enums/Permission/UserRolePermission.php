<?php

namespace App\Enums\Permission;

enum UserRolePermission:string
{
    case VIEW = 'user_role_view';
    case CREATE = 'user_role_create';
    case UPDATE = 'user_role_update';
    case DELETE = 'user_role_delete';
}
