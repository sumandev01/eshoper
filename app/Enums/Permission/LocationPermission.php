<?php
namespace App\Enums\Permission;

enum LocationPermission:string
{
    case VIEW = 'location_view';
    case CREATE = 'location_create';
    case UPDATE = 'location_update';
    case DELETE = 'location_delete';
}