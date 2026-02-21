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

            // ─────────────────────────────────────────────
            // PERMISSIONS
            // ─────────────────────────────────────────────
            $permissions = [

                // ── Artisans ──────────────────────────────
                'voir artisans',
                'créer artisans',
                'modifier artisans',
                'supprimer artisans',
                'approuver artisans',
                'gérer artisans',

                // ── Produits artisanaux ───────────────────
                'voir produits',
                'créer produits',
                'modifier produits',
                'supprimer produits',
                'approuver produits',
                'gérer produits',

                // ── Vendeurs ──────────────────────────────
                'voir vendeurs',
                'créer vendeurs',
                'modifier vendeurs',
                'supprimer vendeurs',
                'approuver vendeurs',
                'gérer vendeurs',

                // ── Plats / Gastronomie ───────────────────
                'voir plats',
                'créer plats',
                'modifier plats',
                'supprimer plats',
                'approuver plats',
                'gérer plats',

                // ── Utilisateurs ──────────────────────────
                'voir utilisateurs',
                'créer utilisateurs',
                'modifier utilisateurs',
                'supprimer utilisateurs',
                'gérer utilisateurs',

                // ── Commandes ─────────────────────────────
                'voir commandes',
                'créer commandes',
                'modifier commandes',
                'annuler commandes',
                'gérer commandes',
                'voir suivi livraison',           // NEW – client tracking

                // ── Devis ─────────────────────────────────
                'voir devis',
                'créer devis',
                'modifier devis',
                'supprimer devis',
                'gérer devis',

                // ── Événements culturels ──────────────────
                'voir événements',
                'créer événements',
                'modifier événements',
                'supprimer événements',
                'gérer événements',

                // ── Avis ──────────────────────────────────
                'voir avis',
                'créer avis',
                'modifier avis',
                'modérer avis',
                'supprimer avis',
                'gérer avis',

                // ── Messages / Contact ─────────────────────
                'voir messages',
                'envoyer messages',               // NEW
                'répondre messages',
                'supprimer messages',
                'gérer messages',

                // ── Favoris ───────────────────────────────
                'gérer favoris',

                // ── Profil ────────────────────────────────
                'modifier profil artisan',        // NEW
                'modifier profil vendeur',        // NEW

                // ── Analytics ─────────────────────────────
                'voir analytics',
                'voir analytics artisan',         // NEW – artisan own stats
                'voir analytics vendeur',         // NEW – vendor own stats

                // ── Paramètres système ────────────────────
                'gérer paramètres généraux',
                'gérer paramètres paiement',
                'gérer paramètres notifications',

                // ── Rôles & permissions ───────────────────
                'gérer rôles et permissions',

                // ── Accès interfaces ──────────────────────
                'access-admin',
                'access-artisan-dashboard',       // NEW
                'access-vendor-dashboard',        // NEW
                'access-client-dashboard',        // NEW
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }

            // ─────────────────────────────────────────────
            // RÔLES
            // ─────────────────────────────────────────────

            // ── Super Admin ───────────────────────────────
            $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
            $superAdmin->syncPermissions(Permission::all());

            // ── Admin ─────────────────────────────────────
            $admin = Role::firstOrCreate(['name' => 'admin']);
            $admin->syncPermissions(
                Permission::whereNotIn('name', ['gérer rôles et permissions'])->pluck('name')
            );

            // ── Artisan ───────────────────────────────────
            $artisan = Role::firstOrCreate(['name' => 'artisan']);
            $artisan->syncPermissions([
                'access-artisan-dashboard',
                // Produits
                'voir produits',
                'créer produits',
                'modifier produits',
                'supprimer produits',       // ses propres produits (filtré en contrôleur)
                // Commandes
                'voir commandes',
                'modifier commandes',
                'gérer commandes',
                // Devis
                'voir devis',
                'modifier devis',
                // Avis
                'voir avis',
                'modifier avis',            // répondre aux avis
                // Messages
                'voir messages',
                'envoyer messages',
                'répondre messages',
                // Profil
                'modifier profil artisan',
                // Analytics
                'voir analytics artisan',
            ]);

            // ── Vendor ────────────────────────────────────
            $vendor = Role::firstOrCreate(['name' => 'vendor']);
            $vendor->syncPermissions([
                'access-vendor-dashboard',
                // Plats
                'voir plats',
                'créer plats',
                'modifier plats',
                'supprimer plats',          // ses propres plats (filtré en contrôleur)
                // Commandes
                'voir commandes',
                'modifier commandes',
                'gérer commandes',
                // Avis
                'voir avis',
                'modifier avis',
                // Messages
                'voir messages',
                'envoyer messages',
                'répondre messages',
                // Profil
                'modifier profil vendeur',
                // Analytics
                'voir analytics vendeur',
            ]);

            // ── Client ────────────────────────────────────
            $client = Role::firstOrCreate(['name' => 'client']);
            $client->syncPermissions([
                'access-client-dashboard',
                // Produits & Plats
                'voir produits',
                'voir plats',
                // Commandes
                'voir commandes',
                'créer commandes',
                'annuler commandes',
                'voir suivi livraison',
                // Devis
                'voir devis',
                'créer devis',
                // Favoris
                'gérer favoris',
                // Avis
                'voir avis',
                'créer avis',
                // Messages
                'voir messages',
                'envoyer messages',
            ]);

            $this->command->info('✅ Rôles et permissions créés/mis à jour avec succès.');
        });
    }
}