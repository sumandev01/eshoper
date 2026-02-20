<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $couponId = $request->couponId;
        $coupon = Coupon::find($couponId);
        return view('web.checkout');
    }
}
