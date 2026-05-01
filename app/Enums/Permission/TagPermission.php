<?php

namespace App\Enums\Permission;

enum TagPermission:string
{
    case CREATE = 'create_tag';
    case VIEW = 'view_tag';
    case UPDATE = 'update_tag';
    case DELETE = 'delete_tag';
}
