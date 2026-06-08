<?php

namespace App\Enums\Permission;

enum TeamMemberPermission:string
{
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
