<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(SubCategory::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->onDelete('set null');
            $table->text('shortDescription')->nullable();
            $table->longText('description')->nullable();
            $table->text('information')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
