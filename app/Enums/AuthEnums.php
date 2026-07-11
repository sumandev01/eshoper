<?php

namespace App\Enums;

enum AuthEnums : string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';
}
