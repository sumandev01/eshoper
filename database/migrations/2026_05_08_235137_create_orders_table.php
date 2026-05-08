<?php

use App\Models\Coupon;
use App\Models\ShippingCost;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            // Amount Details
            $table->float('sub_total')->default(0);
            $table->foreignIdFor(Coupon::class)->nullable()->constrained()->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->float('coupon_discount')->default(0);
            $table->foreignIdFor(ShippingCost::class)->nullable()->constrained()->onDelete('set null');
            $table->string('shipping_location')->nullable();
            $table->float('shipping_charge')->default(0);
            $table->float('grand_total')->default(0);
            // Payment and Status
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
