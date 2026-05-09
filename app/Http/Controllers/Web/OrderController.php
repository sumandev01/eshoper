<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebOrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ShippingCost;
use App\Repositories\OrderRepository;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $couponService;
    protected $orderRepository;

    public function __construct(CouponService $couponService, OrderRepository $orderRepository)
    {
        $this->couponService = $couponService;
        $this->orderRepository = $orderRepository;
    }
    public function index()
    {
        // return view('web.orders.index');
    }

    public function store(WebOrderRequest $request)
    {
        $user = auth('web')->user();
        DB::beginTransaction();
        try {
            $this->orderRepository->OrderByStore($user, $request);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('web.orders')->with('success', 'Order created successfully');
    }
}
