<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cultural_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('type'); // festival, ceremony, celebration
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location');
            $table->string('region')->nullable();
            $table->string('ethnic_origin')->nullable();
            $table->text('traditions')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // yearly, monthly
            $table->integer('notification_days_before')->default(7);
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        Schema::create('event_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultural_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['cultural_event_id', 'user_id']);
        });

        Schema::create('user_event_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type')->nullable(); // null = all events
            $table->string('region')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_notifications');
        Schema::dropIfExists('user_event_subscriptions');
        Schema::dropIfExists('cultural_events');
    }
};
