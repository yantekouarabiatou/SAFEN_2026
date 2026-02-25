<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class, // ✅ 1. Permissions & rôles D'ABORD
            UserSeeder::class,           // ✅ 2. Utilisateurs avec rôles déjà définis
            ArtisanSeeder::class,
            DishSeeder::class,
            VendorSeeder::class,         // avant DishSeeder (clé étrangère)
            ProductSeeder::class,
            CulturalEventSeeder::class,
            ReviewSeeder::class,         // après UserSeeder, ArtisanSeeder, VendorSeeder, ProductSeeder
            MessageSeeder::class,        // Ajout du seeder pour les messages
            DemoOrdersSeeder::class,     // Seeder de démonstration pour commandes + order_items
        ]);
    }
}
