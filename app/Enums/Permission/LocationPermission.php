<?php
namespace App\Enums\Permission;

enum LocationPermission:string
{
    case VIEW = 'view_location';
    case CREATE = 'create_location';
    case UPDATE = 'update_location';
    case DELETE = 'delete_location';
}