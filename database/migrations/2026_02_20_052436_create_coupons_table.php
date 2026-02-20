<?php

use App\Enums\CouponTypeEnums;
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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', array_column(CouponTypeEnums::cases(), 'value'));
            $table->float('amount')->nullable()->default(0);
            $table->float('min_order_amount')->nullable()->default(0);
            $table->float('max_discount')->nullable()->default(0);
            $table->integer('usage_limit')->nullable()->default(0);
            $table->integer('used_count')->nullable()->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
