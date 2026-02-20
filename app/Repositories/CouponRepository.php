<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    public function StoreByRequest($request): Coupon
    {
        return Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'amount' => $request->amount,
            'min_order_amount' => $request->min_order_amount,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'status' => $request->status
        ]);
    }

    public function UpdateByRequest($request, $coupon)
    {
        $coupon->update([
            'amount' => $request->amount,
            'min_order_amount' => $request->min_order_amount,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'status' => $request->status
        ]);
        return $coupon;
    }
}
