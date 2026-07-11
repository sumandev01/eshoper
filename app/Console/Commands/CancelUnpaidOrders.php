<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Enums\PaymentStatusEnums;
use App\Enums\OrderStatusEnums;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid Stripe/SSL orders older than 35 minutes and restore stock.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find unpaid orders created more than 15 minutes ago
        // Exclude 'cashOnDelivery' and 'manual' payment methods because they are manually verified
        $timeLimit = Carbon::now()->subMinutes(35);
        
        $orders = Order::where('payment_status', PaymentStatusEnums::UNPAID->value)
            ->where('order_status', OrderStatusEnums::PENDING->value)
            ->whereNotIn('payment_method', ['cashOnDelivery', 'manual'])
            ->where('created_at', '<', $timeLimit)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No unpaid orders to cancel.');
            return;
        }

        foreach ($orders as $order) {
            DB::beginTransaction();
            try {
                // Update Order Status
                $order->update([
                    'order_status' => OrderStatusEnums::CANCELED->value,
                    'note' => $order->note . "\nCanceled automatically due to non-payment."
                ]);

                // Restore Stock
                foreach ($order->orderProducts as $item) {
                    $inventory = ProductInventory::where('product_id', $item->product_id)
                        ->when($item->size_name, fn($q) => $q->whereHas('size', fn($sq) => $sq->where('name', $item->size_name)))
                        ->when($item->color_name, fn($q) => $q->whereHas('color', fn($cq) => $cq->where('name', $item->color_name)))
                        ->lockForUpdate()
                        ->first();

                    if ($inventory) {
                        $inventory->increment('stock', $item->quantity);
                    }
                    
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }

                // Restore Coupon Usage
                if ($order->coupon_code) {
                    $coupon = \App\Models\Coupon::where('code', $order->coupon_code)->lockForUpdate()->first();
                    if ($coupon) {
                        $coupon->decrement('used_count');
                        \App\Models\CouponUsage::where('order_id', $order->id)->delete();
                    }
                }

                DB::commit();
                $this->info("Order #{$order->order_number} canceled and stock restored.");
                Log::info("Auto-canceled order #{$order->order_number} due to timeout.");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to cancel order #{$order->order_number}: " . $e->getMessage());
                Log::error("Failed to auto-cancel order #{$order->order_number}: " . $e->getMessage());
            }
        }
    }
}
