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
        // S’assurer que les rôles existent
        $this->ensureRolesExist();

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
            $user = User::firstOrCreate(
                ['email' => "artisan{$i}@safen.bj"],
                [
                    'name'     => "Artisan {$i}",
                    'password' => Hash::make('artisan123'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => ['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey-Calavi', 'Djougou'][$i-1] ?? 'Cotonou',
                ]
            );

            $user->assignRole('artisan');

            Artisan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => "Atelier de " . $user->name,
                    'craft'         => $crafts[($i-1) % count($crafts)], // boucle si besoin
                    'city'          => $user->city,
                    'whatsapp'      => $user->phone,
                    'bio'           => "Artisan passionné de {$crafts[($i-1) % count($crafts)]} basé à {$user->city}.",
                ]
            );
        }

        // Vendors (optionnel)
        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "vendor{$i}@safen.bj"],
                [
                    'name'     => "Vendeur {$i}",
                    'password' => Hash::make('vendor123'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => 'Cotonou',
                ]
            );

            $user->assignRole('vendor');
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
