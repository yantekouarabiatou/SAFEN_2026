<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // Liste complète des permissions
            $permissions = [
                // Produits
                'view products',
                'create products',
                'edit products',
                'delete products',
                'publish products',

                // Artisans
                'view artisans',
                'create artisans',
                'edit artisans',
                'delete artisans',

                // Commandes
                'view orders',
                'manage orders',

                // Catégories
                'view categories',
                'manage categories',

                // Utilisateurs & rôles
                'view users',
                'manage users',
                'manage roles & permissions',

                // Avis (← Ajout ici !)
                'view reviews',
                'create reviews',
                'manage reviews',

                // Favoris
                'view favorites',
                'manage favorites',

                // Messages
                'view messages',
                'send messages',
            ];

            // Créer les permissions si elles n'existent pas
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm]);
            }

            // Rôles
            $roles = [
                'admin' => Permission::all()->pluck('name')->toArray(),

                'artisan' => [
                    'view products', 'create products', 'edit products', 'publish products',
                    'view orders',
                    'view reviews',
                ],

                'vendor' => [
                    'view products', 'create products', 'edit products', 'publish products',
                    'view orders',
                ],

                'client' => [
                    'view products',
                    'view orders',
                    'create reviews',          // ← maintenant OK
                    'view reviews',
                    'view favorites',
                    'manage favorites',
                    'send messages',
                ],
            ];

            foreach ($roles as $roleName => $perms) {
                $role = Role::firstOrCreate(['name' => $roleName]);
                $role->syncPermissions($perms);
                $this->command->info("Rôle '{$roleName}' mis à jour avec " . count($perms) . " permissions.");
            }
        });

        $this->command->info('Rôles et permissions créés/mis à jour avec succès.');
    }
}
