<?php

namespace App\Enums;

enum AuthEnums : string
{
    case USER = 'User';
    case ADMIN = 'Admin';
    case SUPER_ADMIN = 'Super Admin';
}
