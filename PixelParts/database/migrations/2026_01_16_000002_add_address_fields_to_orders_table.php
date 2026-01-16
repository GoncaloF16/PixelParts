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
        Schema::table('orders', function (Blueprint $table) {
            // Billing Address (snapshot at order time)
            $table->string('billing_name')->nullable()->after('stripe_id');
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_nif')->nullable();

            // Shipping Address (snapshot at order time)
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_name',
                'billing_address',
                'billing_city',
                'billing_postal_code',
                'billing_country',
                'billing_phone',
                'billing_nif',
                'shipping_name',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_country',
                'shipping_phone',
            ]);
        });
    }
};
