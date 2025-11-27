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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('price_product', 15, 3);
            $table->integer('qty');
            $table->decimal('total_price',15,3);
            $table->string('payment_proof')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('status');
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
