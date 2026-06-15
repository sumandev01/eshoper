<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebOrderRequest;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingAddress;
use App\Repositories\OrderRepository;
use App\Services\CouponService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    protected $couponService;

    protected $orderRepository;

    public function __construct(CouponService $couponService, OrderRepository $orderRepository)
    {
        $this->couponService = $couponService;
        $this->orderRepository = $orderRepository;
    }

    public function index($order)
    {
        $orderId = Order::findOrFail($order->id);

        return view('web.dashboard.order-details', compact('order'));
    }

    public function store(WebOrderRequest $request)
    {
        $user = auth('web')->user();

        DB::beginTransaction();
        try {
            $order = $this->orderRepository->OrderByStore($user, $request);
            DB::commit();

            if ($order->payment_method == 'sslcommerz') {
                return $this->orderRepository->processSSLCommerzPayment($order, $request);
            } elseif ($order->payment_method == 'stripe') {
                return $this->orderRepository->processStripePayment($order, $request);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('web.orderDetails', ['order' => $order->id])->with('success', 'Order created successfully');
    }

    public function orderDetails(Order $order)
    {
        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();

        return view('web.welcome', compact('order', 'orderProducts', 'billingAddress', 'shippingAddress'));
    }
}
