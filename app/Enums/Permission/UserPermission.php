<?php

namespace App\Enums\Permission;

enum UserPermission:string
{
    case VIEW = 'user_view';
    case CREATE = 'user_create';
    case UPDATE = 'user_update';
    case DELETE = 'user_delete';
}
