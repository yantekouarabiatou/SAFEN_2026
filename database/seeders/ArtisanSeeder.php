<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Artisan;
use Illuminate\Support\Str;

class ArtisanSeeder extends Seeder
{
    public function run(): void
    {
        $artisans = [
            [
                'user' => [
                    'name'  => 'Kpadé Koffi',
                ],
                'artisan' => [
                    'craft'            => 'sculpteur',
                    'city'             => 'Abomey',
                    'neighborhood'     => 'Centre-ville',
                    'languages_spoken' => ['Fon', 'Français'],
                    'bio'              => 'Sculpteur de bocio et masques traditionnels depuis plus de 18 ans.',
                    'latitude'         => 7.1859,
                    'longitude'        => 2.0471,
                    'years_experience' => 18,
                ],
            ],

            [
                'user' => [
                    'name'  => 'Aïssatou Dossou',
                ],
                'artisan' => [
                    'craft'            => 'couturier',
                    'city'             => 'Cotonou',
                    'neighborhood'     => 'Fidjrossè',
                    'languages_spoken' => ['Fon', 'Français'],
                    'bio'              => 'Créatrice de tenues traditionnelles Fon et batik moderne.',
                    'years_experience' => 12,
                ],
            ],

            [
                'user' => [
                    'name'  => 'Issa Soulé',
                ],
                'artisan' => [
                    'craft'            => 'forgeron',
                    'city'             => 'Parakou',
                    'neighborhood'     => 'Zongo',
                    'languages_spoken' => ['Bariba', 'Fulfulde', 'Français'],
                    'bio'              => 'Forgeron traditionnel spécialisé dans les outils agricoles et objets rituels.',
                    'years_experience' => 22,
                ],
            ],

            [
                'user' => [
                    'name'  => 'Yussuf Adéyèmi',
                ],
                'artisan' => [
                    'craft'            => 'tisserand',
                    'city'             => 'Porto-Novo',
                    'neighborhood'     => 'Ouando',
                    'languages_spoken' => ['Yoruba', 'Français'],
                    'bio'              => 'Tisserand de pagnes traditionnels Nago et Kente béninois.',
                    'years_experience' => 15,
                ],
            ],

            [
                'user' => [
                    'name'  => 'Mariam Adjovi',
                ],
                'artisan' => [
                    'craft'            => 'potier',
                    'city'             => 'Sè',
                    'neighborhood'     => null,
                    'languages_spoken' => ['Fon'],
                    'bio'              => 'Potière traditionnelle spécialisée dans les jarres et ustensiles en argile.',
                    'years_experience' => 20,
                ],
            ],

            [
                'user' => [
                    'name'  => 'Sabiou Moussa',
                ],
                'artisan' => [
                    'craft'            => 'musicien',
                    'city'             => 'Natitingou',
                    'neighborhood'     => null,
                    'languages_spoken' => ['Ditamari', 'Français'],
                    'bio'              => 'Fabricant et joueur de tam-tam et instruments traditionnels.',
                    'years_experience' => 14,
                ],
            ],
        ];

        foreach ($artisans as $item) {
            // Création User
            $email = Str::slug($item['user']['name']) . rand(100, 999) . '@artisan.bj';

            $user = User::create([
                'name'     => $item['user']['name'],
                'email'    => $email,
                'password' => bcrypt('password'),
            ]);

            // Création Artisan
            Artisan::create(array_merge(
                $item['artisan'],
                [
                    'user_id'       => $user->id,
                    'business_name' => $item['user']['name'] . ' Art',
                    'phone'         => null,
                    'whatsapp'      => null,
                    'verified'      => false,
                    'featured'      => false,
                    'visible'       => true,
                ]
            ));

            $this->command->info("👤 Artisan seedé : {$item['user']['name']}");
        }

        $this->command->info('✅ ArtisanSeeder terminé avec succès');
    }
}
