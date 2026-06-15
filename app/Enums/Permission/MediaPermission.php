<?php

namespace App\Enums\Permission;

enum MediaPermission:string
{
    case VIEW = 'media_view';
    case CREATE = 'media_create';
    case UPDATE = 'media_update';
    case DELETE = 'media_delete';
}
