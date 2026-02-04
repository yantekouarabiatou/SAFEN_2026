<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions pour les produits
        $permissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
            'publish products',
            'view artisans',
            'create artisans',
            'edit artisans',
            'delete artisans',
            'view orders',
            'manage orders',
            'view categories',
            'manage categories',
            'view users',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $artisanRole = Role::create(['name' => 'artisan']);
        $artisanRole->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'view orders',
        ]);

        $clientRole = Role::create(['name' => 'client']);
        $clientRole->givePermissionTo([
            'view products',
        ]);

        $this->command->info('✅ Rôles et permissions créés avec succès.');
    }
}