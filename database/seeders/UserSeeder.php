<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@afriheritage.bj',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+22961234567',
        ]);

        // Sample artisans
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Artisan $i",
                'email' => "artisan$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'artisan',
                'phone' => '+2296' . rand(1000000, 9999999),
                'city' => ['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Ouidah'][$i-1],
            ]);
        }

        // Sample clients
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Client $i",
                'email' => "client$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+2296' . rand(1000000, 9999999),
                'city' => 'Cotonou',
            ]);
        }
    }
}
