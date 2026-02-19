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
        Schema::create('dish_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2)->nullable(); // Prix spécifique chez ce vendeur
            $table->boolean('available')->default(true); // Disponibilité
            $table->text('notes')->nullable(); // Notes spécifiques (variante, spécialité, etc.)
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index(['dish_id', 'vendor_id']);
            $table->unique(['dish_id', 'vendor_id']); // Un plat ne peut être lié qu'une fois à un vendeur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_vendor');
    }
};
