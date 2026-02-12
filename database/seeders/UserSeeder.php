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
        User::updateOrCreate(
            ['email' => 'admin@afriheritage.bj'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+22961234567',
            ]
        );

        // Sample artisans
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "artisan$i@example.com"],
                [
                    'name' => "Artisan $i",
                    'password' => Hash::make('password'),
                    'role' => 'artisan',
                    'phone' => '+2296' . rand(1000000, 9999999),
                    'city' => ['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Ouidah'][$i-1],
                ]
            );
        }

        // Sample clients
        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => "client$i@example.com"],
                [
                    'name' => "Client $i",
                    'password' => Hash::make('password'),
                    'role' => 'client',
                    'phone' => '+2296' . rand(1000000, 9999999),
                    'city' => 'Cotonou',
                ]
            );
        }
    }
}
