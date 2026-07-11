<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\PaymentStatusEnums;
use App\Repositories\OrderRepository;

class StripeController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function success(Request $request)
    {
        $orderNumber = $request->get('order_number');

        if (!$orderNumber) {
            return redirect()->route('cart')->with('error', 'Invalid Payment Request.');
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }

        // If the order is already paid (webhook processed it super fast)
        if ($order->payment_status === PaymentStatusEnums::PAID) {
            return redirect()->route('web.orderDetails', ['order' => $order->id])
                             ->with('checkout_success_order_id', $order->id)
                             ->with('success', 'Payment Successful! TrxID: ' . $order->transaction_id);
        }

        // Otherwise, show the processing view
        return view('web.payment.processing', compact('order'));
    }

    public function status($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json([
            'payment_status' => $order->payment_status,
            'redirect_url' => route('web.orderDetails', ['order' => $order->id])
        ]);
    }

    public function webhook(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderNumber = $session->metadata->order_number ?? null;

            if ($orderNumber) {
                $order = Order::where('order_number', $orderNumber)->first();

                if ($order && $session->payment_status === 'paid') {
                    $this->orderRepository->finalizeOrder($order, $session->payment_intent, $session);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function cancel(Request $request)
    {
        $orderNumber = $request->get('order_number');
        $order = Order::where('order_number', $orderNumber)->first();

        if ($order) {
            $this->orderRepository->failOrder($order);
            return redirect()->route('cart')->with('error', 'Stripe Payment was canceled.');
        }

        return redirect()->route('cart')->with('error', 'Stripe Payment was canceled.');
    }
}
