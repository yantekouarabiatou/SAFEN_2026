<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->morphs('generatable'); // artisan, product, dish
            $table->string('type'); // description, audio, translation
            $table->text('input')->nullable();
            $table->text('output')->nullable();
            $table->string('model')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->decimal('cost', 10, 4)->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_generations');
    }
};
