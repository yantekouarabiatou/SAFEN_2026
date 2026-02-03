<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ArtisanSeeder::class,
            ProductSeeder::class,
            DishSeeder::class,
            //VendorSeeder::class,
        ]);
    }
}
