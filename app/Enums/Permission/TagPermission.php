<?php

namespace App\Enums\Permission;

enum TagPermission:string
{
    case VIEW = 'tag_view';
    case CREATE = 'tag_create';
    case UPDATE = 'tag_update';
    case DELETE = 'tag_delete';
}
