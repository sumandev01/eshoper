<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['stripe', 'paypal', 'cash_on_delivery'];

        // Generate 30 fake orders
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            
            // Random date within the last 6 months to populate the dashboard charts
            $randomDate = Carbon::now()->subDays(rand(0, 180));

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'sub_total' => 0, // Will calculate below
                'shipping_charge' => rand(10, 50),
                'grand_total' => 0, // Will calculate below
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => rand(0, 1) ? 'paid' : 'unpaid',
                'order_status' => $statuses[array_rand($statuses)],
                'shipping_location' => 'Default Location',
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            $subTotal = 0;
            $itemCount = rand(1, 4);

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->discount > 0 ? $product->discount : $product->price;
                $totalPrice = $price * $qty;
                
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $qty,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);

                $subTotal += $totalPrice;
            }

            $order->update([
                'sub_total' => $subTotal,
                'grand_total' => $subTotal + $order->shipping_charge
            ]);
        }
    }
}
