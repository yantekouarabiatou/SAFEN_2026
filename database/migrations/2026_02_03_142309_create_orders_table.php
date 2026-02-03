<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total', 12, 2);
            $table->integer('item_count');

            // Livraison
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_neighborhood')->nullable();
            $table->string('shipping_phone');
            $table->text('shipping_notes')->nullable();

            // Facturation
            $table->string('billing_address');
            $table->string('billing_city');

            // Paiement
            $table->enum('payment_method', ['kkiapay', 'mtn_momo', 'moov_money', 'visa', 'mastercard', 'cash']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable();

            // Frais
            $table->decimal('shipping_fee', 8, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);

            // Informations artisanales
            $table->text('artisan_notes')->nullable();

            // Suivi
            $table->string('tracking_number')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
