<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'data' => [
                    'name'                 => 'Masque Guèlèdè',
                    'name_local'           => 'Masque Guèlèdè',
                    'category'             => 'masque',
                    'subcategory'          => 'traditionnel',
                    'ethnic_origin'        => 'Yoruba',
                    'materials'            => ['bois', 'perles', 'coquillages'],
                    'price'                => 25000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 45,
                    'height'               => 60,
                    'depth'                => 20,
                    'weight'               => 3.5,
                    'description'          => 'Masque traditionnel Guèlèdè utilisé dans les cérémonies',
                    'description_cultural' => 'Masque sacré représentant les ancêtres, utilisé dans les danses rituelles',
                    'description_technical'=> 'Sculpture en bois dur, ornée de perles et coquillages',
                    'slug'                 => 'masque-guelede',
                    'featured'             => true,
                    'artisan_id'           => 1, // Assurez-vous que cet artisan existe
                ],
                'images' => [
                    'guedele.jpg',
                    'GUELEDE2.jpg',
                    'vodoun.jpg',

                ],
            ],
            [
                'data' => [
                    'name'                 => 'Statue Fa',
                    'name_local'           => 'Statue Fa',
                    'category'             => 'sculpture',
                    'subcategory'          => 'divinatoire',
                    'ethnic_origin'        => 'Fon',
                    'materials'            => ['bois', 'pigments'],
                    'price'                => 35000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 30,
                    'height'               => 50,
                    'depth'                => 15,
                    'weight'               => 2.8,
                    'description'          => 'Statue représentant le système divinatoire Fa',
                    'description_cultural' => 'Objet rituel utilisé par les devins pour la divination',
                    'description_technical'=> 'Sculpture en bois d\'iroko, patine naturelle',
                    'slug'                 => 'statue-fa',
                    'featured'             => true,
                    'artisan_id'           => 2,
                ],
                'images' => [
                    'vodoun.jpg',
                ],
            ],
            [
                'data' => [
                    'name'                 => 'Pagne Kenté',
                    'name_local'           => 'Kenté',
                    'category'             => 'tissu',
                    'subcategory'          => 'tissage',
                    'ethnic_origin'        => 'Ashanti',
                    'materials'            => ['coton', 'soie'],
                    'price'                => 15000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 120,
                    'height'               => 200,
                    'depth'                => 0.5,
                    'weight'               => 1.2,
                    'description'          => 'Tissu traditionnel Kenté tissé à la main',
                    'description_cultural' => 'Tissu royal porté lors des cérémonies importantes',
                    'description_technical'=> 'Tissage main, motifs géométriques, couleurs vives',
                    'slug'                 => 'pagne-kente',
                    'featured'             => false,
                    'artisan_id'           => 3,
                ],
                'images' => [
                    'tissu.jpg',
                    'kenté1.jpg',
                ],
            ],

                        [
                'data' => [
                    'name'                 => 'Tambour d\'Afrique',
                    'name_local'           => 'Djembe',
                    'category'             => 'instrument',
                    'subcategory'          => 'tambour',
                    'ethnic_origin'        => 'Bambara',
                    'materials'            => ['bois', 'cuir'],
                    'price'                => 15000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 120,
                    'height'               => 200,
                    'depth'                => 0.5,
                    'weight'               => 1.2,
                    'description'          => 'Tambour traditionnel d\'Afrique de l\'Ouest',
                    'description_cultural' => 'Tambour utilisé dans les cérémonies et les danses',
                    'description_technical'=> 'Bois de type cèdre, cuir de buffle, peinture naturelle',
                    'slug'                 => 'tambour-djembe',
                    'featured'             => false,
                    'artisan_id'           => 3,
                ],
                'images' => [
                    'tamtam.jpg',
                    'musique.jpg',
                ],
            ],

               [
                'data' => [
                    'name'                 => 'Mortier et Pilon',
                    'name_local'           => 'Mortier et Pilon',
                    'category'             => 'cuisine',
                    'subcategory'          => 'ustensile',
                    'ethnic_origin'        => 'NORD BENIN',
                    'materials'            => ['bois', 'pierre'],
                    'price'                => 15000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 120,
                    'height'               => 200,
                    'depth'                => 0.5,
                    'weight'               => 1.2,
                    'description'          => 'Mortier et pilon traditionnel du Nord du Bénin',
                    'description_cultural' => 'Utilisé lors des cérémonies de préparation des repas',
                    'description_technical'=> 'Bois de type cèdre, pierre de granit, peinture naturelle',
                    'slug'                 => 'mortier-pilon',
                    'featured'             => false,
                    'artisan_id'           => 3,
                ],
                'images' => [
                    'mortier2.jpg',
                    'mortier3.jpg',
                    'MORTIER1.jpg',
                ],
            ],

            [
                'data' => [
                    'name'                 => 'Collier en Perles',
                    'name_local'           => 'Kplékplé',
                    'category'             => 'bijou',
                    'subcategory'          => 'collier',
                    'ethnic_origin'        => 'Fon',
                    'materials'            => ['perles', 'laiton'],
                    'price'                => 8000,
                    'currency'             => 'XOF',
                    'stock_status'         => 'in_stock',
                    'width'                => 2,
                    'height'               => 40,
                    'depth'                => 2,
                    'weight'               => 0.3,
                    'description'          => 'Collier traditionnel en perles multicolores',
                    'description_cultural' => 'Porté lors des cérémonies de mariage et d\'initiation',
                    'description_technical'=> 'Perles en verre, fermoir en laiton',
                    'slug'                 => 'collier-perles',
                    'featured'             => true,
                    'artisan_id'           => 1,
                ],
                'images' => [
                    'colier.jpg',
                    'collier1.jpg',
                    'collier2.jpg',
                    'colier3.jpg',


                ],
            ],
        ];

        foreach ($products as $item) {
            // Créer le produit
            $product = Product::create($item['data']);

            // Créer les images associées
            foreach ($item['images'] as $index => $image) {
                ProductImage::create([
                    'product_id'   => $product->id,
                    'image_url'    => "products/{$image}",
                    'is_primary'   => $index === 0, // La première image est principale
                    'order'        => $index + 1,
                ]);
            }

            $this->command->info("🛍️ Produit seedé : {$product->name}");
        }

        $this->command->info('✅ Seeding des produits terminé.');
    }
}