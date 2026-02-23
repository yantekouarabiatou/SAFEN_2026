<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\Product;
use App\Models\Artisan;
use App\Models\Vendor;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Statuts possibles : pending | approved | rejected
     */
    public function run(): void
    {
        // Récupérer les IDs existants
        $userIds    = User::pluck('id')->toArray();
        $products   = Product::pluck('id')->toArray();
        $artisans   = Artisan::pluck('id')->toArray();
        $vendors    = Vendor::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('Aucun utilisateur trouvé. Lancez UserSeeder en premier.');
            return;
        }

        // ─── Commentaires réalistes ───────────────────────────────────────────
        $commentsProduits = [
            'Produit de très bonne qualité, je suis pleinement satisfait de mon achat. La livraison était rapide.',
            'Conforme à la description, rien à redire. Je recommande vivement ce produit.',
            'Bon rapport qualité-prix, mais l\'emballage pourrait être amélioré.',
            'Excellent produit ! Je l\'utilise depuis plusieurs semaines et il répond parfaitement à mes attentes.',
            'Qualité correcte mais pas exceptionnelle. Prix un peu élevé pour ce que c\'est.',
            'Super produit, correspond exactement à ce que je cherchais. Très satisfait.',
            'Déçu par la qualité. Le produit ne ressemble pas vraiment aux photos.',
            'Très bon achat, je recommande sans hésiter à tous mes proches.',
            'Produit solide et bien fabriqué. Rien à redire sur la qualité.',
            'Agréablement surpris par la qualité. Je reviendrai certainement acheter ici.',
        ];

        $commentsArtisans = [
            'Artisan très professionnel, travail soigné et rendu dans les délais. Je suis ravi.',
            'Excellent savoir-faire, le résultat dépasse mes attentes. Je recommande chaudement.',
            'Très à l\'écoute de mes besoins, créations magnifiques. Un vrai talent.',
            'Travail de qualité mais délais un peu longs. Le résultat final est néanmoins superbe.',
            'Artisan passionné qui maîtrise parfaitement son métier. Très satisfait.',
            'Belle réalisation, bonne communication tout au long du projet.',
            'Artisan sérieux et ponctuel. Je referai appel à ses services sans hésitation.',
            'Excellent travail, matériaux de qualité. Je suis pleinement satisfait.',
            'Créations uniques et soignées. L\'artisan est vraiment talentueux.',
            'Professionnel, efficace et de bon conseil. Je recommande vivement.',
        ];

        $commentsVendors = [
            'Vendeur sérieux, produits bien emballés et livraison rapide. Très satisfait.',
            'Bonne expérience d\'achat, le vendeur est réactif et à l\'écoute.',
            'Excellente boutique, large choix de produits de qualité.',
            'Vendeur de confiance, je commande régulièrement ici sans problème.',
            'Quelques petits soucis de communication mais globalement satisfait.',
            'Super vendeur, correspond parfaitement à ce qui est annoncé.',
            'Expérience très positive, je reviendrai certainement acheter ici.',
            'Produits conformes aux descriptions, vendeur professionnel.',
            'Bonne qualité générale, prix compétitifs. Je recommande.',
            'Vendeur fiable et honnête. Très bonne expérience d\'achat.',
        ];

        $statuts = ['pending', 'approved', 'approved', 'approved', 'rejected']; // Plus d'approved

        $reviewsData = [];

        // ─── Reviews sur les Produits ─────────────────────────────────────────
        if (!empty($products)) {
            foreach ($products as $productId) {
                // 2 à 4 reviews par produit
                $nbReviews = rand(2, 4);
                $usersUtilises = [];

                for ($i = 0; $i < $nbReviews; $i++) {
                    // Un utilisateur ne peut reviewer qu'une seule fois le même produit
                    $userId = $this->getUniqueUserId($userIds, $usersUtilises);
                    if (!$userId) continue;
                    $usersUtilises[] = $userId;

                    $reviewsData[] = [
                        'user_id'         => $userId,
                        'reviewable_type' => 'App\Models\Product',
                        'reviewable_id'   => $productId,
                        'rating'          => rand(3, 5),
                        'comment'         => $commentsProduits[array_rand($commentsProduits)],
                        'status'          => $statuts[array_rand($statuts)],
                        'created_at'      => now()->subDays(rand(1, 180)),
                        'updated_at'      => now()->subDays(rand(0, 10)),
                    ];
                }
            }
        }

        // ─── Reviews sur les Artisans ─────────────────────────────────────────
        if (!empty($artisans)) {
            foreach ($artisans as $artisanId) {
                $nbReviews = rand(2, 5);
                $usersUtilises = [];

                for ($i = 0; $i < $nbReviews; $i++) {
                    $userId = $this->getUniqueUserId($userIds, $usersUtilises);
                    if (!$userId) continue;
                    $usersUtilises[] = $userId;

                    $reviewsData[] = [
                        'user_id'         => $userId,
                        'reviewable_type' => 'App\Models\Artisan',
                        'reviewable_id'   => $artisanId,
                        'rating'          => rand(3, 5),
                        'comment'         => $commentsArtisans[array_rand($commentsArtisans)],
                        'status'          => $statuts[array_rand($statuts)],
                        'created_at'      => now()->subDays(rand(1, 180)),
                        'updated_at'      => now()->subDays(rand(0, 10)),
                    ];
                }
            }
        }

        // ─── Reviews sur les Vendors ──────────────────────────────────────────
        if (!empty($vendors)) {
            foreach ($vendors as $vendorId) {
                $nbReviews = rand(2, 4);
                $usersUtilises = [];

                for ($i = 0; $i < $nbReviews; $i++) {
                    $userId = $this->getUniqueUserId($userIds, $usersUtilises);
                    if (!$userId) continue;
                    $usersUtilises[] = $userId;

                    $reviewsData[] = [
                        'user_id'         => $userId,
                        'reviewable_type' => 'App\Models\Vendor',
                        'reviewable_id'   => $vendorId,
                        'rating'          => rand(2, 5),
                        'comment'         => $commentsVendors[array_rand($commentsVendors)],
                        'status'          => $statuts[array_rand($statuts)],
                        'created_at'      => now()->subDays(rand(1, 180)),
                        'updated_at'      => now()->subDays(rand(0, 10)),
                    ];
                }
            }
        }

        // ─── Insertion en base ────────────────────────────────────────────────
        if (!empty($reviewsData)) {
            // Insertion par chunks pour éviter les dépassements de mémoire
            foreach (array_chunk($reviewsData, 50) as $chunk) {
                DB::table('reviews')->insert($chunk);
            }

            $this->command->info(count($reviewsData) . ' avis créés avec succès.');
        } else {
            $this->command->warn('Aucun avis créé. Vérifiez que des produits, artisans et vendors existent.');
        }
    }

    /**
     * Retourner un userId qui n'est pas encore dans $usedIds
     * pour respecter la contrainte "un avis par utilisateur par élément"
     */
    private function getUniqueUserId(array $allIds, array $usedIds): ?int
    {
        $available = array_diff($allIds, $usedIds);
        if (empty($available)) return null;
        return $available[array_rand($available)];
    }
}
