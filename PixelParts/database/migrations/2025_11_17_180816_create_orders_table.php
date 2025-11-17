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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('amount')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('stripe_id')->nullable();
            $table->timestamps();
        });

           Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orders_products');
    }
};
