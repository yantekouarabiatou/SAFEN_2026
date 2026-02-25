<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        // Génère 50 messages
        Message::factory()->count(50)->create();
    }
}
