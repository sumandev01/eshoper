<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentStatusEnums;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SSLCommerzController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');

        if (!$tran_id) {
            return redirect()->route('cart')->with('error', 'Invalid Payment Request.');
        }

        $order = \App\Models\Order::where('order_number', $tran_id)->first();

        if (!$order) {
            return redirect()->route('cart')->with('error', 'Order not found.');
        }

        // If the order is already paid (webhook/IPN processed it super fast)
        if ($order->payment_status === PaymentStatusEnums::PAID->value) {
            return redirect()->route('web.orderDetails', ['order' => $order->id])
                             ->with('success', 'Payment Successful! TrxID: ' . $order->transaction_id);
        }

        // Otherwise, show the processing view
        return view('web.payment.processing', compact('order'));
    }

    // 1. If payment fails (e.g., insufficient balance or wrong PIN)
    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = \App\Models\Order::where('order_number', $tran_id)->first();

        if ($order) {
            $this->orderRepository->failOrder($order);
        }

        // Redirecting the customer back to the cart page with an error message
        return redirect()->route('cart')->with('error', 'Payment Failed! Please try again. Order ID: '.$tran_id);
    }

    // 2. If the customer cancels or goes back from the payment page
    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = \App\Models\Order::where('order_number', $tran_id)->first();

        if ($order) {
            $this->orderRepository->failOrder($order);
        }

        return redirect()->route('cart')->with('error', 'Payment was canceled by you. Order ID: '.$tran_id);
    }

    // 3. IPN (Instant Payment Notification) - Background Verification
    public function ipn(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $status = $request->input('status');

        // If VALID status is received from SSLCommerz
        if ($status == 'VALID' || $status == 'VALIDATED') {

            $order = \App\Models\Order::where('order_number', $tran_id)->first();

            // If the order is found and it is still in UNPAID status
            if ($order && $order->payment_status === PaymentStatusEnums::UNPAID) {

                // Re-verify to confirm the payment (for security purposes)
                $verifyResponse = Http::get($this->getApiUrl('/validator/api/validationserverAPI.php'), [
                    'val_id' => $val_id,
                    'store_id' => config('services.sslcommerz.store_id'),
                    'store_passwd' => config('services.sslcommerz.store_password'),
                    'format' => 'json',
                ]);

                $verification = $verifyResponse->json();

                // If verification is successful, update the status to PAID in the background
                if (isset($verification['status']) && ($verification['status'] == 'VALID' || $verification['status'] == 'VALIDATED')) {
                    $this->orderRepository->finalizeOrder($order, $verification['bank_tran_id'] ?? null, $verification);
                }
            }
        }

        // IPN route does not redirect, it only returns a JSON response to acknowledge receipt
        return response()->json(['message' => 'IPN Processed Successfully']);
    }

    protected function getApiUrl($endpoint)
    {
        $isSandbox = config('services.sslcommerz.is_sandbox');
        $baseUrl = $isSandbox ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';

        return $baseUrl . $endpoint;
    }
}
