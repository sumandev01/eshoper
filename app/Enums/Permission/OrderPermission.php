<?php

namespace App\Enums\Permission;

enum OrderPermission:string
{
    case VIEW = 'order_view';
    case UPDATE = 'order_update';
    case DELETE = 'order_delete';
}
