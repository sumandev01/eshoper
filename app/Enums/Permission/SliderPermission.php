<?php

namespace App\Enums\Permission;

enum SliderPermission:string
{
    case VIEW = 'view_slider';
    case CREATE = 'create_slider';
    case UPDATE = 'update_slider';
    case DELETE = 'delete_slider';
}
