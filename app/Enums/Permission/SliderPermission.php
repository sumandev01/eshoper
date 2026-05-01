<?php

namespace App\Enums\Permission;

enum SliderPermission:string
{
    case CREATE = 'create_slider';
    case VIEW = 'view_slider';
    case UPDATE = 'update_slider';
    case DELETE = 'delete_slider';
}
