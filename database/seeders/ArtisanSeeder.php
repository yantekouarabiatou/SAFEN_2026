<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Artisan;
use App\Models\ArtisanPhoto;
use App\Models\User as ModelsUser;
use Illuminate\Support\Str;

class ArtisanSeeder extends Seeder
{
    public function run(): void
    {
        $artisansData = [
            // 1. Sculpteur - Abomey (Fon)
            [
                'name'             => 'Kpadé Koffi',
                'craft'            => 'sculpteur',
                'city'             => 'Abomey',
                'neighborhood'     => 'Centre-ville',
                'bio'              => 'Sculpteur de bocio, masques Guèlèdè et statuettes vodoun depuis plus de 18 ans. Passionné par la transmission des savoirs traditionnels.',
                'years_experience' => 18,
                'latitude'         => 7.1859,
                'longitude'        => 2.0471,
                'languages'        => ['Fon', 'Français'],
                'pricing_info'     => 'Statuettes : 25 000 - 120 000 FCFA | Masques : 60 000 - 250 000 FCFA',
                'photos'           => ['artisan1.jpg', 'tissu1.jpg', 'tissu2.jpg'],
            ],

            // 2. Couturier - Cotonou (Fon)
            [
                'name'             => 'Aïssatou Dossou',
                'craft'            => 'couturier',
                'city'             => 'Cotonou',
                'neighborhood'     => 'Fidjrossè',
                'bio'              => 'Créatrice de tenues traditionnelles Fon, pagnes tissés et batik moderne. Spécialisée dans les robes de cérémonie et vêtements de tous les jours.',
                'years_experience' => 12,
                'latitude'         => 6.3701,
                'longitude'        => 2.3912,
                'languages'        => ['Fon', 'Français', 'Anglais'],
                'pricing_info'     => 'Tenue complète : 35 000 - 180 000 FCFA | Pagne sur mesure : 20 000 - 80 000 FCFA',
                'photos'           => ['', 'tissu.jpg','atelier.jpg','sandal.jpg','sac.jpg'],
            ],

            // 3. Forgeron - Parakou (Bariba)
            [
                'name'             => 'Issa Soulé',
                'craft'            => 'forgeron',
                'city'             => 'Parakou',
                'neighborhood'     => 'Zongo',
                'bio'              => 'Forgeron traditionnel du Borgou. Fabrication d’outils agricoles (dabas, houes), couteaux rituels et objets décoratifs en fer.',
                'years_experience' => 22,
                'latitude'         => 9.3372,
                'longitude'        => 2.6303,
                'languages'        => ['Bariba', 'Fulfulde', 'Français'],
                'pricing_info'     => 'Outils agricoles : 8 000 - 35 000 FCFA | Pièces décoratives : 40 000 - 150 000 FCFA',
                'photos'           => ['forgeron.jpg'],
            ],

            // 4. Tisserand - Porto-Novo (Yoruba/Nago)
            [
                'name'             => 'Yussuf Adéyèmi',
                'craft'            => 'tisserand',
                'city'             => 'Porto-Novo',
                'neighborhood'     => 'Ouando',
                'bio'              => 'Tisserand de pagnes traditionnels Nago, Kente béninois et tissus à motifs géométriques. Utilise des métiers à tisser manuels ancestraux.',
                'years_experience' => 15,
                'latitude'         => 6.4969,
                'longitude'        => 2.6289,
                'languages'        => ['Yoruba', 'Français'],
                'pricing_info'     => 'Pagne 2m : 45 000 - 120 000 FCFA | Écharpe : 18 000 - 45 000 FCFA',
                'photos'           => ['artisan2.jpg', 'artisan3.jpg', 'tissu1.jpg', 'tissu2.jpg'],
            ],

            // 5. Potier - Sè (Fon)
            [
                'name'             => 'Mariam Adjovi',
                'craft'            => 'potier',
                'city'             => 'Sè',
                'neighborhood'     => null,
                'bio'              => 'Potière traditionnelle. Spécialisée dans les jarres d’eau, marmites, canaris et objets décoratifs en terre cuite.',
                'years_experience' => 20,
                'latitude'         => 6.8200,
                'longitude'        => 2.4833,
                'languages'        => ['Fon'],
                'pricing_info'     => 'Jarres : 15 000 - 60 000 FCFA | Ustensiles cuisine : 5 000 - 25 000 FCFA',
                'photos'           => ['artisan4.jpg', 'pot1.jpg', 'pot2.jpg', 'pot3.jpg'],
            ],

            // 6. Musicien / Fabricant d’instruments - Natitingou (Somba/Ditamari)
            [
                'name'             => 'Sabiou Moussa',
                'craft'            => 'musicien',
                'city'             => 'Natitingou',
                'neighborhood'     => 'Tchamba',
                'bio'              => 'Fabricant et joueur de tam-tam, djembé, gongs et instruments traditionnels Somba. Participe aux cérémonies locales.',
                'years_experience' => 14,
                'latitude'         => 10.2975,
                'longitude'        => 1.3796,
                'languages'        => ['Ditamari', 'Français', 'Fon'],
                'pricing_info'     => 'Tam-tam : 35 000 - 90 000 FCFA | Djembé : 45 000 - 150 000 FCFA',
                'photos'           => ['palletes.jpg'],
            ],
        ];

        foreach ($artisansData as $data) {
            // Création de l'utilisateur
            $email = Str::slug($data['name']) . rand(100, 999) . '@artisan.bj';

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $email,
                'password' => bcrypt('password123'),
                'role'     => 'artisan',
            ]);

            // Numéro WhatsApp / téléphone béninois aléatoire mais réaliste
            $phone = '+229 ' . rand(60, 99) . rand(100000, 999999);

            // Création de l'artisan
            $artisan = Artisan::create([
                'user_id'          => $user->id,
                'business_name'    => $data['name'] . ' - ' . ucfirst($data['craft']),
                'craft'            => $data['craft'],
                'bio'              => $data['bio'],
                'years_experience' => $data['years_experience'],
                'city'             => $data['city'],
                'neighborhood'     => $data['neighborhood'] ?? null,
                'latitude'         => $data['latitude'],
                'longitude'        => $data['longitude'],
                'whatsapp'         => $phone,
                'phone'            => $phone,
                'languages_spoken' => $data['languages'],
                'pricing_info'     => $data['pricing_info'] ?? null,
                'rating_avg'       => rand(35, 50) / 10,   // 3.5 à 5.0
                'rating_count'     => rand(8, 120),
                'verified'         => rand(0, 10) >= 6,     // ~40% vérifiés
                'featured'         => rand(0, 10) >= 8,     // ~20% en vedette
                'visible'          => true,
                'views'            => rand(120, 4800),
            ]);

            // Ajout des photos (portfolio)
            foreach ($data['photos'] as $index => $photoFile) {
                ArtisanPhoto::create([
                    'artisan_id' => $artisan->id,
                    'photo_url'  => "artisans/{$photoFile}",
                    'caption'    => $this->getCaptionForPhoto($photoFile, $data['craft']),
                    'order'      => $index + 1,
                ]);
            }

            $this->command->info("👤 Artisan seedé : {$data['name']} ({$data['craft']}) - {$artisan->rating_avg} ★ ({$artisan->rating_count} avis)");
        }

        $this->command->info('🎉 ArtisanSeeder terminé - ' . count($artisansData) . ' artisans créés');
    }

    /**
     * Génère une légende réaliste pour chaque photo
     */
    private function getCaptionForPhoto(string $file, string $craft): string
    {
        $captions = [
            'sculpture' => 'Sculpture en cours',
            'masque'    => 'Masque traditionnel terminé',
            'atelier'   => 'Vue de l\'atelier',
            'statuette' => 'Statuette vodoun',
            'robe'      => 'Robe traditionnelle',
            'batik'     => 'Batik peint à la main',
            'couture'   => 'Atelier de couture',
            'pagne'     => 'Pagne tissé main',
            'forge'     => 'Forge traditionnelle',
            'outils'    => 'Outils forgés',
            'tissage'   => 'Tissage sur métier traditionnel',
            'pot'       => 'Pot en terre cuite',
            'jarre'     => 'Grande jarre d’eau',
            'tambour'   => 'Tam-tam fabriqué main',
            'djembé'    => 'Djembé sculpté et tendu',
        ];

        foreach ($captions as $key => $caption) {
            if (stripos($file, $key) !== false) {
                return $caption;
            }
        }

        return ucfirst($craft) . ' - Réalisation artisanale';
    }
}