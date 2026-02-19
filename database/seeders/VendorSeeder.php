<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Dish;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer tous les plats existants
        $dishes = Dish::all();

        if ($dishes->isEmpty()) {
            $this->command->error("Aucun plat trouvé. Lancez DishSeeder avant VendorSeeder.");
            return;
        }

        // Liste de vendeurs réalistes avec coordonnées GPS approximatives du Bénin
        $vendorsData = [
            [
                'name'          => 'Chez Maman Victoire',
                'type'          => 'maquis',
                'city'          => 'Cotonou',
                'address'       => 'Fidjrossè, près du Carrefour',
                'phone'         => '+229 96012345',
                'whatsapp'      => '+229 96012345',
                'description'   => 'Spécialiste des plats fon traditionnels : amiwo, eba, gboman...',
                'opening_hours' => 'Lun-Dim 07h-23h',
                'latitude'      => 6.3701,
                'longitude'     => 2.3912,
                'nb_dishes'     => 6, // nombre de plats à associer
            ],

            [
                'name'          => 'Restaurant Le Borgou',
                'type'          => 'restaurant',
                'city'          => 'Parakou',
                'address'       => 'Zongo, près du marché central',
                'phone'         => '+229 97123456',
                'whatsapp'      => '+229 97123456',
                'description'   => 'Spécialités du nord : wassa-wassa, tchoukoutou, igname pilée.',
                'opening_hours' => 'Lun-Sam 08h-22h',
                'latitude'      => 9.3372,
                'longitude'     => 2.6303,
                'nb_dishes'     => 5,
            ],

            [
                'name'          => 'Maquis L’Atan',
                'type'          => 'maquis',
                'city'          => 'Porto-Novo',
                'address'       => 'Ouando, derrière le marché Ouando',
                'phone'         => '+229 95123456',
                'whatsapp'      => '+229 95123456',
                'description'   => 'Bon ablo, akassa et plats yoruba/nago à prix doux.',
                'opening_hours' => 'Mar-Dim 09h-21h',
                'latitude'      => 6.4969,
                'longitude'     => 2.6289,
                'nb_dishes'     => 4,
            ],

            [
                'name'          => 'Chez Tata Adjovi',
                'type'          => 'maquis familial',
                'city'          => 'Abomey',
                'address'       => 'Centre-ville, près du palais royal',
                'phone'         => '+229 98123456',
                'whatsapp'      => '+229 98123456',
                'description'   => 'Excellente igname pilée et sauces fon traditionnelles.',
                'opening_hours' => 'Lun-Sam 10h-20h',
                'latitude'      => 7.1859,
                'longitude'     => 2.0471,
                'nb_dishes'     => 4,
            ],

            [
                'name'          => 'Maquis Le Somba',
                'type'          => 'maquis',
                'city'          => 'Natitingou',
                'address'       => 'Tchamba, près du marché',
                'phone'         => '+229 96123456',
                'whatsapp'      => '+229 96123456',
                'description'   => 'Spécialités Somba et bariba : tam-tam, igname, sauces arachide.',
                'opening_hours' => 'Mer-Dim 08h-22h',
                'latitude'      => 10.2975,
                'longitude'     => 1.3796,
                'nb_dishes'     => 5,
            ],
        ];

        foreach ($vendorsData as $data) {
            // Créer l'utilisateur associé au vendeur
            $user = User::create([
                'name'     => $data['name'],
                'email'    => 'vendor_' . Str::random(6) . '@safen.bj',
                'password' => bcrypt('vendor123'),
                'phone'    => $data['phone'],
            ]);

            // Assigner le rôle Spatie
            $user->assignRole('vendor');

            // Créer le vendeur
            $vendor = Vendor::create([
                'user_id'       => $user->id,
                'name'          => $data['name'],
                'type'          => $data['type'],
                'city'          => $data['city'],
                'address'       => $data['address'],
                'latitude'      => $data['latitude'],
                'longitude'     => $data['longitude'],
                'phone'         => $data['phone'],
                'whatsapp'      => $data['whatsapp'],
                'description'   => $data['description'],
                'opening_hours' => $data['opening_hours'],
                'verified'      => true, // pour tester plus facilement
            ]);

            // Associer aléatoirement entre 3 et le nb max de plats
            $nbDishes = min($data['nb_dishes'], $dishes->count());
            $selectedDishes = $dishes->random($nbDishes);

            $attachData = [];
            foreach ($selectedDishes as $dish) {
                $attachData[$dish->id] = [
                    'price'     => rand(1200, 6500), // prix réaliste en FCFA
                    'available' => true,
                    'notes'     => rand(0, 1) ? 'Spécialité maison' : null,
                ];
            }

            $vendor->dishes()->attach($attachData);

            $this->command->info("✅ Vendeur créé : {$vendor->name} ({$vendor->city}) - {$nbDishes} plats associés");
        }

        $this->command->info('🎉 VendorSeeder terminé.');
    }
}
