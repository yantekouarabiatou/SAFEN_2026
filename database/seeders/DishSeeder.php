<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\DishImage;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $dishes = [
            [
                'data' => [
                    'name'                 => 'Amiwo',
                    'name_local'           => 'Amiwɔ / Djèwɔ',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Fon',
                    'region'               => 'Atlantique, Littoral',
                    'ingredients'          => ['farine de maïs', 'tomate', 'huile rouge', 'oignon', 'piment'],
                    'cultural_description' => 'Pâte de maïs cuite dans une sauce tomate relevée, plat quotidien du sud du Bénin.',
                    'occasions'            => 'Repas familial, quotidien',
                    'slug'                 => 'amiwo',
                ],
                'images' => [
                    'amiwo.jpg',
                    'amiwo2.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Akassa',
                    'name_local'           => 'Akassa',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Fon / Aja',
                    'region'               => 'Ouémé, Atlantique',
                    'ingredients'          => ['farine de maïs fermentée', 'eau', 'sel'],
                    'cultural_description' => 'Galette de maïs fermenté, accompagnée de sauces traditionnelles.',
                    'slug'                 => 'akassa',
                ],
                'images' => [
                    'Bénin.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Ablo',
                    'name_local'           => 'Ablo',
                    'category'             => 'snack',
                    'ethnic_origin'        => 'Fon / Gun',
                    'region'               => 'Ouémé, Porto-Novo',
                    'ingredients'          => ['farine de riz', 'levure', 'sucre'],
                    'cultural_description' => 'Pain vapeur moelleux vendu dans les marchés.',
                    'slug'                 => 'ablo',
                ],
                'images' => [
                    'ablo.jpg',
                    'ablo1.jpg',
                ],
            ],

               [
                'data' => [
                    'name'                 => 'Igame pilée',
                    'name_local'           => 'Igame pilée',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Nord Béninois',
                    'region'               => 'Atacora, Alibori...',
                    'ingredients'          => ['igname', 'Viandes de poulets ,bouef,ou poisson', 'sauce gombo ,feuille,tomate,arahide'],
                    'cultural_description' => 'Plats traditionnel à base d’igname pilée accompagné de différentes sauces.',
                    'slug'                 => 'igame-pilee',
                ],
                'images' => [
                    'ignamepilee1.jpg',
                    'ignamepille.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Gboman',
                    'name_local'           => 'Gboman',
                    'category'             => 'main',
                    'ethnic_origin'        => 'SUD Béninois',
                    'region'               => 'Oueme, Plateau...',
                    'ingredients'          => ['sauce légumes ', 'piment sec', 'huile rouge', 'viande ou poisson','goussi'],
                    'cultural_description' => 'Plat traditionnel à base de légumes locaux accompagné de viande ou poisson.',
                    'slug'                 => 'gboman',
                ],
                'images' => [
                    'gboman.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Wassa-wassa',
                    'name_local'           => 'Wassa-wassa',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Bariba / Peulh',
                    'region'               => 'Borgou, Alibori',
                    'ingredients'          => ['igname pilée', 'sauce feuille', 'viande ou poisson'],
                    'cultural_description' => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'                 => 'wassa-wassa',
                ],
                'images' => [
                    'wassawassa.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'foula',
                    'name_local'           => 'foula',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Bariba / Peulh',
                    'region'               => 'Borgou, Alibori',
                    'ingredients'          => ['sorgho', 'laits'],
                    'cultural_description' => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'                 => 'foula',
                ],
                'images' => [
                    'foula.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Attassi',
                    'name_local'           => 'Attassi',
                    'category'             => 'main',
                    'ethnic_origin'        => ' Nord Béninois',
                    'region'               => 'Borgou, Alibori',
                    'ingredients'          => ['haricot', 'friture', 'viande ou poisson'],
                    'cultural_description' => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'                 => 'attassi',
                ],
                'images' => [
                    'attassi.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'eba',
                    'name_local'           => 'eba',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Fon / Goun',
                    'region'               => 'Oueme, Plateau',
                    'ingredients'          => ['gari', 'sauce feuille', 'viande ou poisson'],
                    'cultural_description' => 'Plat consistant du sud, souvent servi lors des fêtes.',
                    'slug'                 => 'eba',
                ],
                'images' => [
                    'ebasauegombo.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Yovo doko',
                    'name_local'           => 'Yovodoko',
                    'category'             => 'snack',
                    'ethnic_origin'        => 'Fon / Yoruba',
                    'region'               => 'Littoral',
                    'ingredients'          => ['farine de blé', 'sucre', 'levure'],
                    'cultural_description' => 'Beignet sucré emblématique des rues de Cotonou.',
                    'slug'                 => 'yovo-doko',
                ],
                'images' => [
                    'yovodoko.jpg',
                ],
            ],
             [
                'data' => [
                    'name'                 => 'Atchomon ',
                    'name_local'           => 'Atchomon',
                    'category'             => 'snack',
                    'ethnic_origin'        => 'Fon ',
                    'region'               => 'Littoral',
                    'ingredients'          => ['farine de blé', 'sucre', 'levure'],
                    'cultural_description' => 'Beignet sucré emblématique des rues de Cotonou.',
                    'slug'                 => 'Atchonmon',
                ],
                'images' => [
                    'yovodoko.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Télibo',
                    'name_local'           => 'Télibɔ',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Fon',
                    'region'               => 'Zou, Collines',
                    'ingredients'          => ['cossettes d’igname fermentées', 'sauce arachide'],
                    'cultural_description' => 'Plat fermenté typique du centre du Bénin.',
                    'slug'                 => 'telibo',
                ],
                'images' => [
                    'télibo.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Tchakpalo',
                    'name_local'           => 'Tchakpalo',
                    'category'             => 'main',
                    'ethnic_origin'        => 'Fon / Yoruba',
                    'region'               => 'Sud et Centre du Bénin',
                    'ingredients'          => ['farine de maïs', 'eau'],
                    'cultural_description' => 'Pâte de maïs fermentée, consommée avec diverses sauces traditionnelles.',
                    'occasions'            => 'Repas quotidien',
                    'slug'                 => 'thakpalo',
                ],
                'images' => ['tchakpalo.jpg'],
            ],

            // 🥤 Boissons traditionnelles
            [
                'data' => [
                    'name'                 => 'Tchoukoutou',
                    'name_local'           => 'Tchoukoutou',
                    'category'             => 'drink',
                    'ethnic_origin'        => 'Bariba / Dendi',
                    'region'               => 'Nord du Bénin',
                    'ingredients'          => ['sorgho', 'eau'],
                    'cultural_description' => 'Bière traditionnelle à base de sorgho, très consommée lors des cérémonies.',
                    'occasions'            => 'Fêtes, cérémonies, rassemblements',
                    'slug'                 => 'tchoukoutou',
                ],
                'images' => ['thoukoutou.jpg'],
            ],
            [
                'data' => [
                    'name'                 => 'Atan',
                    'name_local'           => 'Atan (vin de palme)',
                    'category'             => 'drink',
                    'ethnic_origin'        => 'Fon / Aja',
                    'region'               => 'Sud du Bénin',
                    'ingredients'          => ['sève de palmier'],
                    'cultural_description' => 'Vin de palme naturel consommé frais ou fermenté.',
                    'occasions'            => 'Réunions sociales, rituels',
                    'slug'                 => 'atan',
                ],
                'images' => ['atan.jpg'],
            ],
        ];

        foreach ($dishes as $item) {
            $dish = Dish::create($item['data']);

            foreach ($item['images'] as $index => $image) {
                DishImage::create([
                    'dish_id'   => $dish->id,
                    'image_url' => "dishes/{$image}",
                    'order'     => $index + 1,
                ]);
            }

            $this->command->info("🍽️ Plat seedé : {$dish->name}");
        }

        $this->command->info('✅ Seeding des plats terminé.');
    }
}
