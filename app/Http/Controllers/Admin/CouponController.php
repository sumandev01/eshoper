<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CouponTypeEnums;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $CouponRepo;

    public function __construct(CouponRepository $CouponRepo) {
        $this->CouponRepo = $CouponRepo;
    }
    public function index(){
        $coupons = Coupon::latest()->get();
        return view('dashboard.coupon.index', compact('coupons'));
    }

    public function add(){
        $couponTypeEnums = CouponTypeEnums::cases();
        return view('dashboard.coupon.add', compact('couponTypeEnums'));
    }

    public function store(Request $request){
        
        $request->validate([
            'code' => 'required|string|min:5|unique:coupons,code',
            'type' => 'required',
            'amount' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'usage_limit' => 'required|numeric|min:0',
            'start_date' => 'required|date|before_or_equal:expire_date|after_or_equal:today',
            'expire_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|numeric|in:0,1',
        ]);
        
        $coupon = $this->CouponRepo->StoreByRequest($request);

        return redirect()->route('coupon.index')->with('success', 'Coupon created successfully');
    }

    public function edit($coupon){
        $coupon = Coupon::find($coupon);
        $couponTypeEnums = CouponTypeEnums::cases();
        return view('dashboard.coupon.edit', compact('coupon', 'couponTypeEnums'));
    }

    public function update(Request $request, $coupon){
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'usage_limit' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:exists:coupons,'. $coupon,
            'expire_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|numeric|in:0,1',
        ]);
        $coupon = Coupon::find($coupon);
        $this->CouponRepo->UpdateByRequest($request, $coupon);
        return redirect()->route('coupon.index')->with('success', 'Coupon updated successfully');
    }

    public function destroy($couponId){
        $coupon = Coupon::find($couponId);
        $coupon->delete();
        return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully');
    }
}
