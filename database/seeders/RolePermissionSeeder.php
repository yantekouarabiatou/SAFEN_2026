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
        // Reset le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // ---------- PERMISSIONS EN FRANÇAIS ----------
            $permissions = [
                // Artisans
                'voir artisans',
                'créer artisans',
                'modifier artisans',
                'supprimer artisans',
                'approuver artisans',
                'gérer artisans',

                // Produits artisanaux
                'voir produits',
                'créer produits',
                'modifier produits',
                'supprimer produits',
                'approuver produits',
                'gérer produits',

                // Vendeurs (gastronomie)
                'voir vendeurs',
                'créer vendeurs',
                'modifier vendeurs',
                'supprimer vendeurs',
                'approuver vendeurs',
                'gérer vendeurs',

                // Plats / Gastronomie
                'voir plats',
                'créer plats',
                'modifier plats',
                'supprimer plats',
                'approuver plats',
                'gérer plats',

                // Utilisateurs
                'voir utilisateurs',
                'créer utilisateurs',
                'modifier utilisateurs',
                'supprimer utilisateurs',
                'gérer utilisateurs',

                // Commandes (produits & plats)
                'voir commandes',
                'modifier commandes',
                'annuler commandes',
                'gérer commandes',

                // Devis
                'voir devis',
                'gérer devis',

                // Événements culturels
                'voir événements',
                'créer événements',
                'modifier événements',
                'supprimer événements',
                'gérer événements',

                // Avis
                'voir avis',
                'créer avis',
                'modérer avis',
                'supprimer avis',
                'gérer avis',

                // Messages / Contact
                'voir messages',
                'répondre messages',
                'supprimer messages',
                'gérer messages',

                // Favoris (client)
                'gérer favoris',

                // Analytics
                'voir analytics',

                // Paramètres généraux
                'gérer paramètres généraux',
                'gérer paramètres paiement',
                'gérer paramètres notifications',

                // Rôles & permissions (super-admin uniquement)
                'gérer rôles et permissions',
                'access-admin',                  

            ];

            // Création des permissions
            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }

            // ---------- RÔLES ET ASSIGNATIONS ----------
            // Super Admin : toutes les permissions
            $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
            $superAdmin->syncPermissions(Permission::all());

            // Admin : tout sauf 'gérer rôles et permissions'
            $admin = Role::firstOrCreate(['name' => 'admin']);
            $admin->syncPermissions(Permission::where('name', '!=', 'gérer rôles et permissions')->pluck('name'));

            // Artisan : gestion de ses propres produits, commandes, avis...
            $artisan = Role::firstOrCreate(['name' => 'artisan']);
            $artisan->syncPermissions([
                'voir produits',
                'créer produits',
                'modifier produits',
                'voir commandes',
                'gérer commandes',
                'voir avis',
                'répondre messages',
                'gérer favoris', // optionnel
            ]);

            // Vendor : gestion de ses plats, commandes...
            $vendor = Role::firstOrCreate(['name' => 'vendor']);
            $vendor->syncPermissions([
                'voir plats',
                'créer plats',
                'modifier plats',
                'voir commandes',
                'gérer commandes',
                'voir avis',
                'répondre messages',
            ]);

            // Client : voir produits/plats, gérer favoris, laisser avis
            $client = Role::firstOrCreate(['name' => 'client']);
            $client->syncPermissions([
                'voir produits',
                'voir plats',
                'gérer favoris',
                'voir avis',
                'créer avis',
                'voir commandes',
            ]);

            $this->command->info('✅ Rôles et permissions créés/mis à jour en français.');
        });
    }
}
