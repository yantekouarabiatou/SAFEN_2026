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
        $dishIds = Dish::pluck('id')->toArray();

        if (empty($dishIds)) {
            $this->command->warn("⚠ Aucun plat trouvé. Lance DishSeeder avant VendorSeeder.");
            return;
        }

        $vendors = [
            [
                'name'          => 'Chez Maman Victoire',
                'type'          => 'maquis',
                'city'          => 'Cotonou',
                'address'       => 'Fidjrossè, près du Carrefour',
                'phone'         => '+229 96012345',
                'whatsapp'      => '+229 96012345',
                'description'   => 'Spécialiste des plats fon traditionnels.',
                'opening_hours' => 'Lun-Dim 07h-23h',
                'latitude'      => 6.3701,
                'longitude'     => 2.3912,
                'specialties'   => $this->randomSpecialties($dishIds, 3),
            ],
        ];

        foreach ($vendors as $data) {

            // ✅ Création User
            $user = User::create([
                'name'     => $data['name'],
                'email'    => 'vendor_' . Str::random(8) . '@exemple.bj',
                'password' => bcrypt('password123'),
                'phone'    => $data['whatsapp'],
            ]);

            // ✅ Assignation rôle Spatie
            $user->assignRole('vendor');

            // ✅ Création Vendor
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
                'verified'      => rand(0, 10) > 7,
            ]);

            // ✅ Pivot avec price obligatoire
            $attachData = [];

            foreach ($data['specialties'] as $dishId) {
                $attachData[$dishId] = [
                    'price' => rand(500, 5000), // FCFA
                ];
            }

            $vendor->dishes()->attach($attachData);

            $this->command->info("✅ Vendor créé : {$vendor->name}");
        }
    }

    /**
     * Retourne X plats aléatoires
     */
    private function randomSpecialties(array $dishIds, int $count): array
    {
        return collect($dishIds)
            ->shuffle()
            ->take($count)
            ->values()
            ->toArray();
    }
}
