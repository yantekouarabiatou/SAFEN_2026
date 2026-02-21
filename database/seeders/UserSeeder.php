<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Vider le cache Spatie avant toute assignation
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ─────────────────────────────────────────────
        // 1. SUPER ADMIN
        // ─────────────────────────────────────────────
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'Super Administrateur',
                'password' => Hash::make('super1234'),
                'phone'    => '+22960000000',
                'city'     => 'Cotonou',
            ]
        );
        $superAdmin->syncRoles('super-admin');

        // ─────────────────────────────────────────────
        // 2. ADMIN
        // ─────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('admin1234'),
                'phone'    => '+22961111111',
                'city'     => 'Cotonou',
            ]
        );
        $admin->syncRoles('admin');

        // ─────────────────────────────────────────────
        // 3. ARTISANS (5 comptes + profil Artisan)
        // ─────────────────────────────────────────────
        $crafts = [
            'tisserand', 'bijoutier', 'sculpteur', 'potier', 'couturier',
            'forgeron', 'menuisier', 'tanneur', 'coiffeur', 'autre',
        ];

        $cities = ['Cotonou', 'Porto-Novo', 'Parakou', 'Abomey', 'Ouidah'];

        foreach (range(1, 5) as $i) {
            $city = $cities[array_rand($cities)];

            $user = User::firstOrCreate(
                ['email' => "artisan{$i}@example.com"],
                [
                    'name'     => "Artisan {$i}",
                    'password' => Hash::make('password'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => $city,
                ]
            );

            // ✅ syncRoles évite les doublons si le seeder tourne plusieurs fois
            $user->syncRoles('artisan');

            // Profil artisan lié
            Artisan::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => "Atelier de {$user->name}",
                    'craft'         => $crafts[array_rand($crafts)],
                    'city'          => $city,
                    'status'        => 'approved',
                ]
            );
        }

        // ─────────────────────────────────────────────
        // 4. VENDORS (3 comptes)
        // ─────────────────────────────────────────────
        foreach (range(1, 3) as $i) {
            $user = User::firstOrCreate(
                ['email' => "vendor{$i}@example.com"],
                [
                    'name'     => "Vendeur {$i}",
                    'password' => Hash::make('password'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => $cities[array_rand($cities)],
                ]
            );

            $user->syncRoles('vendor');
        }

        // ─────────────────────────────────────────────
        // 5. CLIENTS (10 comptes)
        // ─────────────────────────────────────────────
        foreach (range(1, 10) as $i) {
            $user = User::firstOrCreate(
                ['email' => "client{$i}@safen.bj"],
                [
                    'name'     => "Client {$i}",
                    'password' => Hash::make('client123'),
                    'phone'    => '+2296' . rand(1000000, 9999999),
                    'city'     => $cities[array_rand($cities)],
                ]
            );

            $user->syncRoles('client');
        }

        // ─────────────────────────────────────────────
        // Résumé
        // ─────────────────────────────────────────────
        $this->command->info('✅ Utilisateurs créés   : ' . User::count());
        $this->command->info('✅ Profils artisans     : ' . Artisan::count());
        $this->command->info('✅ Rôles assignés avec succès (syncRoles).');
    }
}