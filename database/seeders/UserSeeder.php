<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
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

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'Super Administrateur',
                'password' => Hash::make('super1234'),
                'phone'    => '+22960000000',
                'city'     => 'Cotonou',
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Admin classique
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('admin1234'),
                'phone'    => '+22961111111',
                'city'     => 'Cotonou',
            ]
        );
        $admin->assignRole('admin');

        // Liste des métiers VALIDES selon ton enum
        $crafts = [
            'tisserand',
            'bijoutier',
            'sculpteur',
            'potier',
            'couturier',
            'forgeron',
            'menuisier',
            'tanneur',
            'coiffeur',
            'autre',
        ];

        // Artisans
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

        // Clients
        for ($i = 1; $i <= 15; $i++) {
            User::firstOrCreate(
                ['email' => "client{$i}@safen.bj"],
                [
                    'name'     => "Client {$i}",
                    'password' => Hash::make('client123'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => ['Cotonou', 'Porto-Novo', 'Parakou'][rand(0, 2)],
                ]
            )->assignRole('client');
        }

        $this->command->info('Utilisateurs créés : ' . User::count());
        $this->command->info('Profils artisans créés : ' . Artisan::count());
        $this->command->info('Rôles assignés avec succès.');
    }

    private function ensureRolesExist(): void
    {
        $roles = ['super-admin', 'admin', 'artisan', 'vendor', 'client'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info('Rôles de base vérifiés/créés.');
    }
}
