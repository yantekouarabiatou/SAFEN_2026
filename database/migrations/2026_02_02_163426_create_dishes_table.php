<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_local')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('ethnic_origin')->nullable();
            $table->string('region')->nullable();
            
            // CORRECTION : Utiliser les mêmes valeurs que dans le modèle
            $table->enum('category', [
                'plat_principal',
                'entree',
                'accompagnement',
                'dessert',
                'boisson',
                'sauce',
                'snack',
            ])->nullable();
            
            $table->json('ingredients')->nullable();
            $table->text('preparation')->nullable(); // Était 'recipe' dans votre migration
            $table->text('description')->nullable(); // Ajouté (existe dans le modèle)
            $table->text('history')->nullable(); // Ajouté (existe dans le modèle)
            $table->json('nutritional_info')->nullable();
            $table->text('occasions')->nullable();
            $table->json('restaurants')->nullable(); // Ajouté (existe dans le modèle)
            $table->string('season')->nullable();
            $table->string('slug')->unique();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dishes');
    }
};