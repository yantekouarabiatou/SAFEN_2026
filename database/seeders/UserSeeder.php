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
        // 1. Créer / Vérifier les rôles AVANT tout
        $this->createRoles();

        // 2. Super Admin
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

        // 3. Admin classique
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

        // 4. Artisans (avec profil Artisan)
        $crafts = [
            'tisserand', 'bijoutier', 'sculpteur', 'potier', 'couturier',
            'forgeron', 'menuisier', 'tanneur', 'coiffeur', 'autre',
        ];

        foreach (range(1, 5) as $i) {
            $user = User::firstOrCreate(
                ['email' => "artisan$i@example.com"],
                [
                    'name'     => "Artisan $i",
                    'password' => Hash::make('password'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => ['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Ouidah'][array_rand(['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Ouidah'])],
                ]
            );

            $user->assignRole('artisan');

            // Créer le profil artisan associé
            Artisan::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => "Atelier de " . $user->name,
                    'craft'         => $crafts[array_rand($crafts)],
                    'city'          => $user->city,
                    'status'        => 'approved', // ou 'pending' selon ta logique
                ]
            );
        }

        // 5. Clients simples
        foreach (range(1, 10) as $i) {
            $user = User::firstOrCreate(
                ['email' => "client$i@safen.bj"],
                [
                    'name'     => "Client $i",
                    'password' => Hash::make('client123'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => ['Cotonou', 'Porto-Novo', 'Parakou'][rand(0, 2)],
                ]
            );

            $user->assignRole('client');
        }

        $this->command->info('Utilisateurs créés : ' . User::count());
        $this->command->info('Profils artisans créés : ' . Artisan::count());
        $this->command->info('Rôles assignés avec succès.');
    }

    /**
     * Créer tous les rôles nécessaires si ils n'existent pas
     */
    private function createRoles(): void
    {
        $roles = [
            'super-admin',
            'admin',
            'artisan',
            'vendor',
            'client',
            // Ajoute ici tous les rôles que tu utilises dans ton projet
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $this->command->info('Rôles de base vérifiés/créés (' . count($roles) . ').');
    }
}