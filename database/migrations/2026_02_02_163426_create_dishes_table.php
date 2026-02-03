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
            $table->enum('category', ['main', 'drink', 'snack', 'dessert', 'sauce']);
            $table->json('ingredients')->nullable();
            $table->text('recipe')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->text('cultural_description')->nullable();
            $table->text('occasions')->nullable();
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
