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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->foreignId('shop_id')->nullable()->constrained();
            // TODO: multiple coupon?
            $table->foreignId('coupon_id')->nullable()->constrained();
            $table->decimal('applied_price');
            $table->decimal('refunded')->default(0);
            $table->string('status');

            $table->string('trade_no');
            $table->string('callback_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
