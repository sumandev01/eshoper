<?php

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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->default(1)->comment('1: Left, 2: Right');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->foreignIdFor(\App\Models\Media::class, 'image_id')->nullable()->constrained('media')->onDelete('set null');
            $table->string('link_type')->nullable();
            $table->string('link_ref_id')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
