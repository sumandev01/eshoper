<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ShippingAddress;
use App\Models\Courier;
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
        $couriers = Courier::where('status', 1)->get();
        return view('dashboard.order.edit', compact('order', 'orderStatusEnums', 'paymentStatusEnums', 'couriers'));
    }

    public function update(Request $request, $order)
    {
        $order = Order::findOrFail($order);

        $request->validate([
            'order_status' => ['required', Rule::enum(OrderStatusEnums::class)],
            'payment_status' => ['required', Rule::enum(PaymentStatusEnums::class)],
            'courier_id' => 'nullable|exists:couriers,id',
            'courier_tracking_id' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ];

        // Check if marking as delivered but not paid
        if ($request->order_status === OrderStatusEnums::DELIVERED->value && $request->payment_status !== PaymentStatusEnums::PAID->value) {
            return back()->with('error', 'Order cannot be marked as Delivered unless Payment Status is Paid!');
        }

        // Only update courier info if status is shipped
        if ($request->order_status === OrderStatusEnums::SHIPPED->value) {
            $updateData['courier_id'] = $request->courier_id;
            $updateData['courier_tracking_id'] = $request->courier_tracking_id;
        }

        $order->update($updateData);

        return redirect()->route('admin.order.index')->with('success', 'Order updated successfully.');
    }
}
