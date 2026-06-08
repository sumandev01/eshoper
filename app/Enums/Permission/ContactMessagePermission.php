<?php

namespace App\Enums\Permission;

enum ContactMessagePermission:string
{
    case VIEW = 'view_contact_message';
    case UPDATE = 'update_contact_message';
    case DELETE = 'delete_contact_message';
}
