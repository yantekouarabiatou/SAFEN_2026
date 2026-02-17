<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artisans', function (Blueprint $table) {

            $table->id();

            // Relation utilisateur
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Infos artisan
            $table->string('business_name')->nullable();

            $table->enum('craft', [
                'tisserand',
                'sculpteur',
                'potier',
                'forgeron',
                'couturier',
                'mecanicien',
                'vulcanisateur',
                'coiffeur',
                'menuisier',
                'bijoutier',
                'tanneur',
                'corroyeur',
                'musicien',
                'autre'
            ]);

            $table->text('bio')->nullable();
            $table->integer('years_experience')->nullable();

            // Localisation
            $table->string('city');
            $table->string('neighborhood')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Contact
            $table->string('whatsapp')->nullable();
            $table->string('phone')->nullable();

            // Langues parlées
            $table->json('languages_spoken')->nullable();

            // Tarifs
            $table->text('pricing_info')->nullable();

            // Statut validation
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->text('rejection_reason')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // Admin qui valide
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Notes & visibilité
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);

            $table->boolean('verified')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('visible')->default(true);

            $table->integer('views')->default(0);

            // Dates
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artisans');
    }
};
