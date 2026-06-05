<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $orders = Order::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('user_name', 'like', '%' . $search . '%');
                try {
                    $formattedDate = date('Y-m-d', strtotime($search));
                    $q->orWhere('created_at', 'like', '%' . $formattedDate . '%');
                } catch (\Exception $e) {
                    
                }
            });
        }, function ($query) {
            return $query;
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.order.index', compact('orders'));
    }


    public function view($order)
    {
        $order = Order::findOrFail($order);

        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();
        return view('dashboard.order.view', compact('order', 'orderProducts', 'billingAddress', 'shippingAddress'));
    }

    public function edit($order)
    {
        $order = Order::findOrFail($order);
        $orderStatusEnums = OrderStatusEnums::cases();
        $paymentStatusEnums = PaymentStatusEnums::cases();
        return view('dashboard.order.edit', compact('order', 'orderStatusEnums', 'paymentStatusEnums'));
    }

    public function update(Request $request, $order)
    {
        $order = Order::findOrFail($order);

        $request->validate([
            'order_status' => ['required', Rule::enum(OrderStatusEnums::class)],
            'payment_status' => ['required', Rule::enum(PaymentStatusEnums::class)],
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('order.index')->with('success', 'Order updated successfully.');
    }
}
