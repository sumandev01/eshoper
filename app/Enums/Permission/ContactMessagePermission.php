<?php

namespace App\Enums\Permission;

enum ContactMessagePermission:string
{
    case VIEW = 'contact_message_view';
    case UPDATE = 'contact_message_update';
    case DELETE = 'contact_message_delete';
}
