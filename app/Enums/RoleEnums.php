<?php

namespace App\Enums;

enum RoleEnums:string
{
    case Super_Admin = 'super_admin';
    case Admin = 'admin';
    case User = 'user';
}
