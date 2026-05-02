<?php

namespace App\Enums\Permission;

enum MediaPermission:string
{
    case VIEW = 'media.view';
    case CREATE = 'media.create';
    case UPDATE = 'media.update';
    case DELETE = 'media.delete';
}
