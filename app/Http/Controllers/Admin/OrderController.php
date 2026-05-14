<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders= Order::all()->sortByDesc('id');
        return view('dashboard.order.index', compact('orders'));
    }


    public function view($order)
    {
        $order = Order::find($order);
        $orderProducts = OrderProduct::where('order_id', $order->id)->get();
        $billingAddress = BillingAddress::where('order_id', $order->id)->first();
        $shippingAddress = ShippingAddress::where('order_id', $order->id)->first();
        return view('dashboard.order.view', compact('order', 'orderProducts', 'billingAddress', 'shippingAddress'));
    }
}
