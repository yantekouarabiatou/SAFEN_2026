<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name')->nullable();
            $table->enum('craft', [
                'tisserand', 'sculpteur', 'potier', 'forgeron', 'couturier',
                'mecanicien', 'vulcanisateur', 'coiffeur', 'menuisier',
                'bijoutier', 'tanneur', 'corroyeur', 'musicien', 'autre'
            ]);
            $table->text('bio')->nullable();
            $table->integer('years_experience')->nullable();
            $table->string('city');
            $table->string('neighborhood')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('phone')->nullable();
            $table->json('languages_spoken')->nullable()->comment('JSON array of languages');
            $table->text('pricing_info')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->boolean('verified')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('visible')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artisans');
    }
};
