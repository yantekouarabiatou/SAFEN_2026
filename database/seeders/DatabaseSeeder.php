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
            VendorSeeder::class,        
            ProductSeeder::class,
            CulturalEventSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
