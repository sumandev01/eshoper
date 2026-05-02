<?php

namespace App\Enums\Permission;

enum UserRolePermission:string
{
    case VIEW = 'view_user_role';
    case CREATE = 'create_user_role';
    case UPDATE = 'update_user_role';
    case DELETE = 'delete_user_role';
}
