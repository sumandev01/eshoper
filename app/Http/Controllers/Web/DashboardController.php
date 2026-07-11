<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ProductReview;
use App\Models\Setting;
use App\Models\ShippingAddress;
use App\Models\UserAddress;
use App\Models\Wishlist;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();
        $orders = Order::whereUserId($user->id)->orderBy('id', 'desc')->get()->take(5);
        $wishlists = Wishlist::where('user_id', $user->id)->get();
        return view('web.dashboard.index', compact('orders', 'wishlists'));
    }

    public function orders()
    {
        $user = auth('web')->user();
        $orders = Order::whereUserId($user->id)->orderBy('id', 'desc')->get();
        return view('web.dashboard.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();
        return view('web.dashboard.order-details', compact('order', 'orderProducts', 'billingAddress', 'shippingAddress'));
    }


    public function orderProducts()
    {
        $user = auth('web')->user();
        $orderProducts = OrderProduct::whereUserId($user->id)->orderBy('id', 'desc')->paginate(10);
        $productReviews = ProductReview::whereUserId($user->id)->get()->keyBy('product_id');

        return view('web.dashboard.order-products', compact('orderProducts', 'productReviews'));
    }
}
