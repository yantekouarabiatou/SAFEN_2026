<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artisan_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_local')->nullable();
            $table->string('audio_url')->nullable();
            $table->enum('category', [
                'masque', 'sculpture', 'tissu', 'bijou', 'instrument',
                'decoration', 'peinture', 'vannerie', 'poterie','cuisine', 'autre'
            ]);
            $table->string('subcategory')->nullable();
            $table->string('ethnic_origin')->nullable();
            $table->json('materials')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'preorder', 'made_to_order'])->default('in_stock');
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('depth', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('description_cultural')->nullable();
            $table->text('description_technical')->nullable();
            $table->string('slug')->unique();
            $table->boolean('featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('order_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
