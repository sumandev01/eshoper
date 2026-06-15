<?php

namespace App\Enums\Permission;

enum AboutPagePermission:string
{
    case VIEW = 'about_view';
    case UPDATE = 'about_update';
}
