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

            // Réduction des longueurs pour éviter dépassement index
            $table->string('session_id', 100);
            $table->string('status', 50);
            $table->integer('item_count')->default(0);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();

            // Index composite sécurisé
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
