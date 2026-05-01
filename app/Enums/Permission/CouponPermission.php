<?php

namespace App\Enums\Permission;

enum CouponPermission:string
{
    case CREATE = 'create_coupon';
    case VIEW = 'view_coupon';
    case UPDATE = 'update_coupon';
    case DELETE = 'delete_coupon';
}
