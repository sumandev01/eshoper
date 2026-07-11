<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    // Order Tracking
    public function orderTracking()
    {
        return view('web.tracking.order-tracking');
    }

    public function orderTrackingDetails(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = Order::where('order_number', $request->order_number)->with('orderProducts')->first();
        
        if ($order) {
            return view('web.tracking.order-tracking-result', compact('order'));
        } else {
            return back()->with('error', 'Order not found');
        }
    }
}
