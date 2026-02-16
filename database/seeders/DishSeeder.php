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
                    'name'           => 'Amiwo',
                    'name_local'     => 'Amiwɔ / Djèwɔ',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Fon',
                    'region'         => 'Atlantique, Littoral',
                    'ingredients'    => ['farine de maïs', 'tomate', 'huile rouge', 'oignon', 'piment'],
                    'description'    => 'Pâte de maïs cuite dans une sauce tomate relevée, plat quotidien du sud du Bénin.',
                    'occasions'      => 'Repas familial, quotidien',
                    'slug'           => 'amiwo',
                ],
                'images' => [
                    'amiwo.jpg',
                    'amiwo2.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Akassa',
                    'name_local'     => 'Akassa',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Fon / Aja',
                    'region'         => 'Ouémé, Atlantique',
                    'ingredients'    => ['farine de maïs fermentée', 'eau', 'sel'],
                    'description'    => 'Galette de maïs fermenté, accompagnée de sauces traditionnelles.',
                    'slug'           => 'akassa',
                ],
                'images' => [
                    'Bénin.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Ablo',
                    'name_local'     => 'Ablo',
                    'category'       => 'snack',
                    'ethnic_origin'  => 'Fon / Gun',
                    'region'         => 'Ouémé, Porto-Novo',
                    'ingredients'    => ['farine de riz', 'levure', 'sucre'],
                    'description'    => 'Pain vapeur moelleux vendu dans les marchés.',
                    'slug'           => 'ablo',
                ],
                'images' => [
                    'ablo.jpg',
                    'ablo1.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Igname pilée',
                    'name_local'     => 'Igname pilée',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Nord Béninois',
                    'region'         => 'Atacora, Alibori',
                    'ingredients'    => ['igname', 'viandes de poulets, bœuf ou poisson', 'sauce gombo, feuille, tomate, arachide'],
                    'description'    => 'Plat traditionnel à base d\'igname pilée accompagné de différentes sauces.',
                    'slug'           => 'igname-pilee',
                ],
                'images' => [
                    'ignamepilee1.jpg',
                    'ignamepille.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Gboman',
                    'name_local'     => 'Gboman',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'SUD Béninois',
                    'region'         => 'Ouémé, Plateau',
                    'ingredients'    => ['sauce légumes', 'piment sec', 'huile rouge', 'viande ou poisson', 'goussi'],
                    'description'    => 'Plat traditionnel à base de légumes locaux accompagné de viande ou poisson.',
                    'slug'           => 'gboman',
                ],
                'images' => [
                    'gboman.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Wassa-wassa',
                    'name_local'     => 'Wassa-wassa',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Bariba / Peulh',
                    'region'         => 'Borgou, Alibori',
                    'ingredients'    => ['igname pilée', 'sauce feuille', 'viande ou poisson'],
                    'description'    => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'           => 'wassa-wassa',
                ],
                'images' => [
                    'wassawassa.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Foula',
                    'name_local'     => 'Foula',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Bariba / Peulh',
                    'region'         => 'Borgou, Alibori',
                    'ingredients'    => ['sorgho', 'lait'],
                    'description'    => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'           => 'foula',
                ],
                'images' => [
                    'foula.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Attassi',
                    'name_local'     => 'Attassi',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Nord Béninois',
                    'region'         => 'Borgou, Alibori',
                    'ingredients'    => ['haricot', 'friture', 'viande ou poisson'],
                    'description'    => 'Plat consistant du nord, souvent servi lors des fêtes.',
                    'slug'           => 'attassi',
                ],
                'images' => [
                    'attassi.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Eba',
                    'name_local'     => 'Eba',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Fon / Goun',
                    'region'         => 'Ouémé, Plateau',
                    'ingredients'    => ['gari', 'sauce feuille', 'viande ou poisson'],
                    'description'    => 'Plat consistant du sud, souvent servi lors des fêtes.',
                    'slug'           => 'eba',
                ],
                'images' => [
                    'ebasauegombo.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Yovo doko',
                    'name_local'     => 'Yovodoko',
                    'category'       => 'snack',
                    'ethnic_origin'  => 'Fon / Yoruba',
                    'region'         => 'Littoral',
                    'ingredients'    => ['farine de blé', 'sucre', 'levure'],
                    'description'    => 'Beignet sucré emblématique des rues de Cotonou.',
                    'slug'           => 'yovo-doko',
                ],
                'images' => [
                    'yovodoko.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Atchomon',
                    'name_local'     => 'Atchomon',
                    'category'       => 'snack',
                    'ethnic_origin'  => 'Fon',
                    'region'         => 'Littoral',
                    'ingredients'    => ['farine de blé', 'sucre', 'levure'],
                    'description'    => 'Beignet sucré emblématique des rues de Cotonou.',
                    'slug'           => 'atchomon',
                ],
                'images' => [
                    'yovodoko.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Télibo',
                    'name_local'     => 'Télibɔ',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Fon',
                    'region'         => 'Zou, Collines',
                    'ingredients'    => ['cossettes d\'igname fermentées', 'sauce arachide'],
                    'description'    => 'Plat fermenté typique du centre du Bénin.',
                    'slug'           => 'telibo',
                ],
                'images' => [
                    'télibo.jpg',
                ],
            ],

            [
                'data' => [
                    'name'           => 'Tchakpalo',
                    'name_local'     => 'Tchakpalo',
                    'category'       => 'plat_principal',
                    'ethnic_origin'  => 'Fon / Yoruba',
                    'region'         => 'Sud et Centre du Bénin',
                    'ingredients'    => ['farine de maïs', 'eau'],
                    'description'    => 'Pâte de maïs fermentée, consommée avec diverses sauces traditionnelles.',
                    'occasions'      => 'Repas quotidien',
                    'slug'           => 'tchakpalo',
                ],
                'images' => ['tchakpalo.jpg'],
            ],

            // 🥤 Boissons traditionnelles
            [
                'data' => [
                    'name'           => 'Tchoukoutou',
                    'name_local'     => 'Tchoukoutou',
                    'category'       => 'boisson',
                    'ethnic_origin'  => 'Bariba / Dendi',
                    'region'         => 'Nord du Bénin',
                    'ingredients'    => ['sorgho', 'eau'],
                    'description'    => 'Bière traditionnelle à base de sorgho, très consommée lors des cérémonies.',
                    'occasions'      => 'Fêtes, cérémonies, rassemblements',
                    'slug'           => 'tchoukoutou',
                ],
                'images' => ['thoukoutou.jpg'],
            ],

            [
                'data' => [
                    'name'           => 'Atan',
                    'name_local'     => 'Atan (vin de palme)',
                    'category'       => 'boisson',
                    'ethnic_origin'  => 'Fon / Aja',
                    'region'         => 'Sud du Bénin',
                    'ingredients'    => ['sève de palmier'],
                    'description'    => 'Vin de palme naturel consommé frais ou fermenté.',
                    'occasions'      => 'Réunions sociales, rituels',
                    'slug'           => 'atan',
                ],
                'images' => ['atan.jpg'],
            ],
        ];

        foreach ($dishes as $item) {
            $dish = Dish::updateOrCreate(
                ['slug' => $item['data']['slug']],
                $item['data']
            );

            foreach ($item['images'] as $index => $image) {
                DishImage::updateOrCreate(
                    [
                        'dish_id' => $dish->id,
                        'image_url' => "dishes/{$image}"
                    ],
                    [
                        'order' => $index + 1,
                    ]
                );
            }

            $this->command->info("🍽️ Plat seedé : {$dish->name}");
        }

        $this->command->info('✅ Seeding des plats terminé.');
    }
}