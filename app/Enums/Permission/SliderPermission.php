<?php

namespace App\Enums\Permission;

enum SliderPermission:string
{
    case VIEW = 'slider_view';
    case CREATE = 'slider_create';
    case UPDATE = 'slider_update';
    case DELETE = 'slider_delete';
}
