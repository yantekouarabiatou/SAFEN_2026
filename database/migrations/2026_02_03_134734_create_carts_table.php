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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Identifiant utilisateur (si connecté)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');

            // Identifiant de session (pour les visiteurs non connectés)
            $table->string('session_id')
                  ->nullable()
                  ->index();

            // Statut du panier
            $table->string('status')
                  ->default('active')
                  ->index(); // active, abandoned, completed, etc.

            // Total du panier (en FCFA)
            $table->decimal('total', 12, 2)
                  ->default(0.00);

            // Nombre total d'articles (somme des quantités)
            $table->unsignedInteger('item_count')
                  ->default(0);

            // Données supplémentaires de checkout (adresse, mode de paiement, notes, etc.)
            $table->json('checkout_data')
                  ->nullable();

            $table->timestamps();

            // Index composites utiles
            $table->index(['user_id', 'status']);
            $table->index(['session_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
