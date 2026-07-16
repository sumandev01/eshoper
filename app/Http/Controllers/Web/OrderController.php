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

class OrderController extends Controller
{
    protected $couponService;

    protected $orderRepository;

    public function __construct(CouponService $couponService, OrderRepository $orderRepository)
    {
        $this->couponService = $couponService;
        $this->orderRepository = $orderRepository;
    }

    public function index(Order $order)
    {
        $this->authorize('view', $order);
        return view('web.dashboard.order-details', compact('order'));
    }

    public function store(WebOrderRequest $request)
    {
        $user = auth('web')->user();

        DB::beginTransaction();
        try {
            $order = $this->orderRepository->OrderByStore($user, $request);

            $redirect = null;
            if ($order->payment_method == 'sslcommerz') {
                $redirect = $this->orderRepository->processSSLCommerzPayment($order, $request);
            } elseif ($order->payment_method == 'stripe') {
                $redirect = $this->orderRepository->processStripePayment($order, $request);
            }

            DB::commit();

            if ($redirect) {
                return $redirect;
            }

            return redirect()->route('web.orderDetails', ['order' => $order->id])
                             ->with('checkout_success_order_id', $order->id)
                             ->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('cart')->with('error', $e->getMessage());
        }
    }

    public function orderDetails(Order $order)
    {
        $this->authorize('view', $order);
        // One-time view security: Prevent direct URL access to the success page
        if (session('checkout_success_order_id') != $order->id) {
            return redirect()->route('user.orders');
        }

        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();

        return view('web.payment.success', compact('order', 'orderProducts', 'billingAddress', 'shippingAddress'));
    }
}
