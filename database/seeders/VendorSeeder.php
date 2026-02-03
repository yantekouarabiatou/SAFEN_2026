<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Dish;           // pour associer des spécialités

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // On suppose que des plats existent déjà (DishSeeder exécuté avant)
        $dishes = Dish::all()->pluck('id')->toArray();

        if ($dishes->isEmpty()) {
            $this->command->warn("Aucun plat trouvé → exécute DishSeeder d'abord pour de vraies spécialités");
            // On continue quand même avec des IDs fictifs si besoin
        }

        $vendors = [
            // Cotonou – maquis / rue
            [
                'name'          => 'Chez Maman Victoire',
                'type'          => 'maquis',
                'city'          => 'Cotonou',
                'address'       => 'Fidjrossè, près du Carrefour',
                'phone'         => '+229 96012345',
                'whatsapp'      => '+229 96012345',
                'description'   => 'Spécialiste des plats fon traditionnels : amiwo, akassa, télibo. Ambiance familiale et sauce bien relevée.',
                'opening_hours' => 'Lun-Dim 07h-23h',
                'latitude'      => 6.3701,
                'longitude'     => 2.3912,
                'specialties'   => $this->randomSpecialties($dishes, 3), // 3 plats
            ],

            // Porto-Novo – street food
            [
                'name'          => 'Yovo Doko de Tante Awa',
                'type'          => 'street_vendor',
                'city'          => 'Porto-Novo',
                'address'       => 'Marché Ouando, entrée principale',
                'phone'         => '+229 97087654',
                'whatsapp'      => '+229 97087654',
                'description'   => 'Les meilleurs yovo doko croustillants du marché depuis 15 ans. Aussi ablo et beignets de haricot.',
                'opening_hours' => 'Mar-Dim 06h-14h',
                'latitude'      => 6.4969,
                'longitude'     => 2.6289,
                'specialties'   => $this->randomSpecialties($dishes, 2),
            ],

            // Parakou – nord
            [
                'name'          => 'Toubani du Borgou – Issa',
                'type'          => 'market_stand',
                'city'          => 'Parakou',
                'address'       => 'Grand Marché de Parakou, allée centrale',
                'phone'         => '+229 68094567',
                'whatsapp'      => '+229 68094567',
                'description'   => 'Toubani frais tous les matins, accompagné de sauce arachide ou piment. Spécialité bariba authentique.',
                'opening_hours' => 'Lun-Sam 05h-13h',
                'latitude'      => 9.3372,
                'longitude'     => 2.6303,
                'specialties'   => $this->randomSpecialties($dishes, 2),
            ],

            // Abomey – cuisinière à domicile / commande
            [
                'name'          => 'Maman Adjoua – Traiteur traditionnel',
                'type'          => 'home_cook',
                'city'          => 'Abomey',
                'address'       => 'Quartier Dodji, derrière le palais',
                'phone'         => '+229 95033445',
                'whatsapp'      => '+229 95033445',
                'description'   => 'Préparation sur commande : amiwo, wassa-wassa, sauces feuilles. Idéal pour cérémonies et événements.',
                'opening_hours' => 'Sur commande',
                'latitude'      => 7.1859,
                'longitude'     => 2.0471,
                'specialties'   => $this->randomSpecialties($dishes, 4),
            ],

            // Cotonou – restaurant un peu plus structuré
            [
                'name'          => 'La Case du Bénin',
                'type'          => 'restaurant',
                'city'          => 'Cotonou',
                'address'       => 'Junction Jonathan, Cadjèhoun',
                'phone'         => '+229 21301234',
                'whatsapp'      => '+229 96009876',
                'description'   => 'Restaurant valorisant la gastronomie béninoise : tchigan, akassa, sodabi maison, plats du jour variés.',
                'opening_hours' => 'Lun-Dim 11h-22h',
                'latitude'      => 6.3580,
                'longitude'     => 2.4167,
                'specialties'   => $this->randomSpecialties($dishes, 5),
            ],
        ];

        foreach ($vendors as $data) {
            // Créer un compte utilisateur optionnel (si la cuisinière/gérant veut se connecter)
            $user = null;
            if (rand(0, 1) === 1) { // 50% des cas
                $user = User::create([
                    'name'     => explode(' – ', $data['name'])[0] ?? $data['name'],
                    'email'    => 'vendor' . rand(100, 999) . '@exemple.bj',
                    'password' => bcrypt('password123'),
                    'role'     => 'vendor', // ← à adapter si tu as ce rôle
                    'phone'    => $data['whatsapp'],
                ]);
            }

            $vendor = Vendor::create([
                'user_id'       => $user?->id,
                'name'          => $data['name'],
                'type'          => $data['type'],
                'city'          => $data['city'],
                'address'       => $data['address'] ?? null,
                'latitude'      => $data['latitude'] ?? null,
                'longitude'     => $data['longitude'] ?? null,
                'phone'         => $data['phone'],
                'whatsapp'      => $data['whatsapp'],
                'specialties'   => json_encode($data['specialties']),
                'description'   => $data['description'],
                'opening_hours' => $data['opening_hours'],
                'verified'      => rand(0, 10) > 7, // ~30% vérifiés pour la démo
            ]);

            $this->command->info("Vendor créé : " . $vendor->name);
        }
    }

    /**
     * Sélectionne aléatoirement X IDs de plats (ou tableau vide si pas de plats)
     */
    private function randomSpecialties($dishIds, int $count): array
    {
        if (empty($dishIds)) {
            return [];
        }

        $selected = [];
        $available = $dishIds->toArray();

        for ($i = 0; $i < $count; $i++) {
            if (empty($available)) break;
            $key = array_rand($available);
            $selected[] = $available[$key];
            unset($available[$key]);
        }

        return array_values($selected);
    }
}
