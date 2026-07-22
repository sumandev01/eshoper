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
            'code' => ['required', 'string', 'min:5', 'alpha_dash', 'unique:coupons,code'],
            'type' => 'required',
            'amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|required_if:type,percentage|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'usage_limit' => 'required|numeric|min:0',
            'limit_per_user' => 'required|numeric|min:1',
            'start_date' => 'nullable|date|before_or_equal:expire_date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|numeric|in:0,1',
        ]);
        
        $coupon = $this->CouponRepo->StoreByRequest($request);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully');
    }

    public function edit($coupon){
        $coupon = Coupon::findOrFail($coupon);
        $couponTypeEnums = CouponTypeEnums::cases();
        return view('dashboard.coupon.edit', compact('coupon', 'couponTypeEnums'));
    }

    public function update(Request $request, $coupon){
        $request->validate([
            'code' => ['required', 'string', 'min:5', 'alpha_dash', 'unique:coupons,code,' . $coupon],
            'amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|required_if:type,percentage|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'usage_limit' => 'required|numeric|min:0',
            'limit_per_user' => 'required|numeric|min:1',
            'start_date' => 'nullable|date|before_or_equal:expire_date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|numeric|in:0,1',
        ]);
        $coupon = Coupon::findOrFail($coupon);
        $this->CouponRepo->UpdateByRequest($request, $coupon);
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully');
    }

    public function destroy($couponId){
        $coupon = Coupon::findOrFail($couponId);
        $coupon->delete();
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon deleted successfully');
    }
}
