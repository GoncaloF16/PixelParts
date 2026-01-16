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
        Schema::table('users', function (Blueprint $table) {
            // Billing Address
            $table->string('billing_name')->nullable()->after('role');
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->default('Portugal');
            $table->string('billing_phone')->nullable();
            $table->string('billing_nif')->nullable();

            // Shipping Address
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->default('Portugal');
            $table->string('shipping_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
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
