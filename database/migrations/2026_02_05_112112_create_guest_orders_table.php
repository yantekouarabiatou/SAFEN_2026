<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Informations client invité
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->string('guest_address')->nullable();
            $table->string('guest_city')->nullable();
            $table->string('guest_country')->default('Bénin');
            
            // Montants
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('deposit_amount', 10, 2); // Acompte
            $table->decimal('remaining_amount', 10, 2); // Reste à payer
            
            // Statuts
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // Paiement
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('deposit_paid_at')->nullable();
            $table->timestamp('fully_paid_at')->nullable();
            
            // Données additionnelles
            $table->json('order_items'); // Stockage des produits commandés
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_orders');
    }
};