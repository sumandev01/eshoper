<?php

use App\Models\Media;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->float('price');
            $table->float('buy_price')->nullable()->default(0);
            $table->float('discount')->nullable()->default(0);
            $table->float('tax')->nullable()->default(0);
            $table->float('stock')->default(0);
            $table->integer('rating')->default(0);
            $table->integer('is_trending')->default(0);
            $table->integer('views')->default(0);
            $table->integer('status')->default(0);
            $table->foreignIdFor(Media::class)->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
