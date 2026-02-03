<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dish_vendor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dish_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();

            $table->decimal('price', 8, 2);
            $table->boolean('available')->default(true);

            $table->timestamps();

            $table->unique(['dish_id', 'vendor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dish_vendor');
    }
};
