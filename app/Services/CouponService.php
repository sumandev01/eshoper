<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function getCouponPrice($couponId, $subTotalPrice)
    {
        $coupon = Coupon::find($couponId);

        if (!$coupon->status == 1) {
            return redirect()->back()->with('error', 'Coupon is not valid');
        }

        $isValid = $coupon->start_date <= now() && $coupon->expire_date >= now();
        if (!$isValid) {
            return redirect()->back()->with('error', 'Coupon is not valid');
        }

        $hasLimit = ($coupon->usage_limit - $coupon->used_count) > 0;
        if (!$hasLimit) {
            return redirect()->back()->with('error', 'Coupon usage limit exceeded');
        }

        $minAmount = $coupon->min_order_amount;
        if ($subTotalPrice < $minAmount) {
            return redirect()->back()->with('error', 'Coupon is not valid');
        }
        
        $couponDiscount = 0;
        if ($coupon->type == 'fixed') {
            $couponDiscount = $coupon->amount;
        } elseif ($coupon->type == 'percentage') {
            $couponDiscount = ($subTotalPrice * $coupon->amount) / 100;
        }

        $finalDiscountPrice = $subTotalPrice - $couponDiscount;

        return ([
            'couponDiscount' => $couponDiscount,
            'finalDiscountPrice' => $finalDiscountPrice,
            'couponCode' => $coupon->code
        ]);
    }

    public function getAjaxCouponPrice($couponCode, $cartSubTotal)
    {
        $coupon = Coupon::where('code', $couponCode)->where('status', 1)->first();

        if (!$coupon) {
            return ['status' => 'error', 'message' => 'Coupon not found'];
        }

        $isValid = $coupon->start_date <= now() && $coupon->expire_date >= now();
        if (!$isValid) {
            return ['status' => 'error', 'message' => 'Coupon is not valid'];
        }

        $hasLimit = ($coupon->usage_limit - $coupon->used_count) > 0;
        if (!$hasLimit) {
            return ['status' => 'error', 'message' => 'Coupon usage limit exceeded'];
        }

        if ($cartSubTotal < $coupon->min_order_amount) {
            return ['status' => 'error', 'message' => 'Minimum order amount not met'];
        }

        $couponDiscount = 0;
        if ($coupon->type == 'fixed') {
            $couponDiscount = $coupon->amount;
        } elseif ($coupon->type == 'percentage') {
            $couponDiscount = ($cartSubTotal * $coupon->amount) / 100;
        }

        $finalDiscountPrice = $cartSubTotal - $couponDiscount;

        return [
            'status' => 'success',
            'message' => 'Coupon applied successfully',
            'couponDiscount' => $couponDiscount,
            'finalDiscountPrice' => $finalDiscountPrice,
            'couponId' => $coupon->id
        ];
    }
}
