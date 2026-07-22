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
        $paymentStatus = $request->payment_status;
        $orderStatus = $request->order_status;

        $dateFilter = $request->get('date_filter', '');
        $startDate = null;
        $endDate = null;

        if ($dateFilter) {
            $now = now();
            switch ($dateFilter) {
                case 'today':
                    $startDate = $now->copy()->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    break;
                case 'yesterday':
                    $startDate = $now->copy()->subDay()->startOfDay();
                    $endDate = $now->copy()->subDay()->endOfDay();
                    break;
                case 'this_week':
                    $startDate = $now->copy()->startOfWeek();
                    $endDate = $now->copy()->endOfWeek();
                    break;
                case 'last_7_days':
                    $startDate = $now->copy()->subDays(6)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    break;
                case 'this_month':
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    break;
                case 'last_month':
                    $startDate = $now->copy()->subMonth()->startOfMonth();
                    $endDate = $now->copy()->subMonth()->endOfMonth();
                    break;
                case 'this_year':
                    $startDate = $now->copy()->startOfYear();
                    $endDate = $now->copy()->endOfYear();
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
                    }
                    break;
            }
        }

        // Summary Metric Cards Data
        $totalOrdersCount      = Order::count();
        $pendingOrdersCount    = Order::where('order_status', OrderStatusEnums::PENDING->value)->count();
        $processingOrdersCount = Order::where('order_status', OrderStatusEnums::PROCESSING->value)->count();
        $totalRevenue          = Order::where('payment_status', PaymentStatusEnums::PAID->value)->sum('grand_total');

        // Dynamic Status Counts for Navigation Tabs
        $statusCounts = Order::selectRaw('order_status, count(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        // Optimized Query with Eager Loading (Fixes N+1 problem)
        $orders = Order::with('orderProducts')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                        ->orWhere('user_name', 'like', '%' . $search . '%');
                    try {
                        $formattedDate = date('Y-m-d', strtotime($search));
                        $q->orWhere('created_at', 'like', '%' . $formattedDate . '%');
                    } catch (\Exception $e) {
                        
                    }
                });
            })
            ->when($paymentStatus, function ($query) use ($paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->when($orderStatus, function ($query) use ($orderStatus) {
                $query->where('order_status', $orderStatus);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Auto-fix: If page is out of bounds due to filter change, redirect to page 1
        if ($orders->currentPage() > $orders->lastPage() && $orders->lastPage() > 0) {
            return redirect()->to($request->fullUrlWithQuery(['page' => 1]));
        }

        return view('dashboard.order.index', compact(
            'orders',
            'dateFilter',
            'startDate',
            'endDate',
            'totalOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'totalRevenue',
            'statusCounts'
        ));
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
