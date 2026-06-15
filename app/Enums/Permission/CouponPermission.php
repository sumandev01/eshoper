<?php

namespace App\Enums\Permission;

enum CouponPermission:string
{
    case VIEW = 'coupon_view';
    case CREATE = 'coupon_create';
    case UPDATE = 'coupon_update';
    case DELETE = 'coupon_delete';
}
