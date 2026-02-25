<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $sender = User::inRandomOrder()->first() ?? User::factory()->create();
        $receiver = User::inRandomOrder()->where('id', '!=', $sender->id)->first() ?? User::factory()->create();
        $conversation = Conversation::inRandomOrder()->first() ?? Conversation::factory()->create();

        return [
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['text', 'quote', 'order', 'image']),
            'reference_id' => null,
            'read_at' => $this->faker->boolean(60) ? now() : null,
        ];
    }
}
