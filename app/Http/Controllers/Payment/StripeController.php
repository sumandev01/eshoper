<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\PaymentStatusEnums;

class StripeController extends Controller
{
public function success(Request $request)
{
    $sessionId = $request->get('session_id');
    $orderNumber = $request->get('order_number');

    if (!$sessionId || !$orderNumber) {
        return redirect()->route('cart')->with('error', 'Invalid Payment Request.');
    }

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $order = Order::where('order_number', $orderNumber)->first();

        if ($session->payment_status === 'paid') {
            if ($order && $order->payment_status === PaymentStatusEnums::UNPAID) {
                $order->update([
                    'payment_status' => PaymentStatusEnums::PAID->value,
                    'transaction_id' => $session->payment_intent,
                    'payment_data' => json_encode($session)
                ]);

                return redirect()->route('web.orderDetails', ['order' => $order->id])
                                 ->with('success', 'Stripe Payment Successful! TrxID: ' . $session->payment_intent);
            }
        }

        if ($order) {
            return redirect()->route('web.orderDetails', ['order' => $order->id])
                             ->with('error', 'Payment not completed or already processed.');
        }

        return redirect()->route('cart')->with('error', 'Payment not completed or already processed.');

    } catch (\Exception $e) {
        return redirect()->route('cart')->with('error', 'Stripe Error: ' . $e->getMessage());
    }
}

public function cancel(Request $request)
{
    $orderNumber = $request->get('order_number');
    $order = Order::where('order_number', $orderNumber)->first();

    if ($order) {
        return redirect()->route('user.orderDetails', ['order' => $order->id])
                         ->with('error', 'Stripe Payment was canceled.');
    }

    return redirect()->route('cart')->with('error', 'Stripe Payment was canceled.');
}
}