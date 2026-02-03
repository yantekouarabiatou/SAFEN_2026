<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Artisan;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $artisans = Artisan::all();

        if ($artisans->isEmpty()) {
            $this->command->warn('⚠️ Lance ArtisanSeeder avant ProductSeeder');
            return;
        }

        // Mapping slug → images
        $imageMapping = [
            'masque-guelede' => [
                'guelede1.jpg',
                'guelede2.jpg',
            ],
            'zangbeto' => [
                'zangbeto.jpg',
            ],
            'tissu-kente-benin' => [
                'kente1.jpg',
                'kente2.jpg',
            ],
            'calebasse' => [
                'calebasse1.jpg',
            ],
            'mortier-bois' => [
                'mortier1.jpg',
                'mortier2.jpg',
            ],
            'tamtam-traditionnel' => [
                'tamtam.jpg',
            ],
        ];

        $products = [
            [
                'name'                 => 'Masque Guèlèdè',
                'name_local'           => 'Guèlèdè',
                'category'             => 'masque',
                'ethnic_origin'        => 'Yoruba',
                'materials'            => ['bois', 'pigments naturels', 'fibres'],
                'price'                => 85000,
                'currency'             => 'XOF',
                'stock_status'         => 'in_stock',
                'description_cultural' => 'Masque rituel utilisé pour honorer les mères et les divinités féminines.',
                'slug'                 => 'masque-guelede',
            ],
            [
                'name'                 => 'Zangbeto',
                'category'             => 'masque',
                'ethnic_origin'        => 'Fon',
                'materials'            => ['paille', 'tissu', 'raphia'],
                'price'                => 65000,
                'currency'             => 'XOF',
                'stock_status'         => 'made_to_order',
                'description_cultural' => 'Masque gardien de la nuit, symbole de protection.',
                'slug'                 => 'zangbeto',
            ],
            [
                'name'                 => 'Tissu Kente béninois',
                'category'             => 'tissu',
                'ethnic_origin'        => 'Fon',
                'materials'            => ['coton'],
                'price'                => 45000,
                'currency'             => 'XOF',
                'slug'                 => 'tissu-kente-benin',
            ],
            [
                'name'                 => 'Calebasse décorative',
                'category'             => 'decoration',
                'materials'            => ['calebasse'],
                'price'                => 20000,
                'currency'             => 'XOF',
                'slug'                 => 'calebasse',
            ],
            [
                'name'                 => 'Mortier en bois',
                'category'             => 'poterie',
                'materials'            => ['bois'],
                'price'                => 30000,
                'currency'             => 'XOF',
                'slug'                 => 'mortier-bois',
            ],
            [
                'name'                 => 'Tam-tam traditionnel',
                'category'             => 'instrument',
                'materials'            => ['bois', 'peau animale'],
                'price'                => 90000,
                'currency'             => 'XOF',
                'slug'                 => 'tamtam-traditionnel',
            ],
        ];

        foreach ($products as $data) {
            $data['artisan_id'] = $artisans->random()->id;

            $product = Product::create($data);

            if (!empty($imageMapping[$product->slug])) {
                foreach ($imageMapping[$product->slug] as $index => $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => "products/{$image}",
                        'is_primary' => $index === 0,
                        'order'      => $index + 1,
                    ]);
                }
            }

            $this->command->info("🛍️ Produit seedé : {$product->name}");
        }

        $this->command->info('✅ ProductSeeder terminé');
    }
}
