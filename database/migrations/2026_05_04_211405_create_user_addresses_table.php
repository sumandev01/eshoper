<?php

use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->enum('type', ['billing', 'shipping'])->default('shipping');
            $table->string('address_default')->default('0'); // 0 for not default, 1 for default
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->foreignIdFor(Division::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(District::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Thana::class)->nullable()->constrained()->onDelete('set null');
            $table->text('address')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
