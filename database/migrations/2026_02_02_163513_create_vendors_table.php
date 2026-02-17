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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Relation avec l'utilisateur (un user = un vendeur)
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('name', 100);

            // Type de vendeur : plus large que l'enum pour permettre des variantes réalistes
            $table->string('type', 80)
                  ->default('maquis')
                  ->comment('Ex: maquis, maquis familial, restaurant traditionnel, gargote, street food, cantine, traiteur');

            $table->string('address', 255)->nullable();
            $table->string('city', 100);

            // Géolocalisation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Contacts
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();

            // Spécialités : IDs des plats (JSON)
            $table->json('specialties')->nullable()
                  ->comment('Tableau JSON des IDs de plats proposés');

            $table->text('description')->nullable();

            $table->string('opening_hours', 100)->nullable()
                  ->comment('Ex: Lun-Dim 07h-23h');

            // Stats
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('verified')->default(false);

            $table->timestamps();

            // Index pour performances
            $table->index('city');
            $table->index(['latitude', 'longitude']);
            $table->index('verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
