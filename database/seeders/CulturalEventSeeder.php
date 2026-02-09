<?php

namespace Database\Seeders;

use App\Models\CulturalEvent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CulturalEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'name' => 'Fête du Vodoun',
                'description' => 'Célébration nationale du Vodoun, religion traditionnelle béninoise. Cérémonies, danses et rituels dans tout le pays, particulièrement à Ouidah.',
                'type' => 'ceremony',
                'event_date' => Carbon::create(now()->year, 1, 10),
                'location' => 'Ouidah',
                'region' => 'Atlantique',
                'ethnic_origin' => 'Fon',
                'traditions' => 'Rituels vodoun, danses sacrées, offrandes',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 7,
            ],
            [
                'name' => 'Festival International des Masques et Arts',
                'description' => 'Grand festival célébrant les masques traditionnels et l\'art contemporain béninois.',
                'type' => 'festival',
                'event_date' => Carbon::create(now()->year, 2, 15),
                'location' => 'Abomey',
                'region' => 'Zou',
                'ethnic_origin' => 'Divers',
                'traditions' => 'Masques gelede, zangbeto, défilés',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 10,
            ],
            [
                'name' => 'Festival Quintessence',
                'description' => 'Festival de musique et arts célébrant la culture africaine contemporaine.',
                'type' => 'festival',
                'event_date' => Carbon::create(now()->year, 12, 10),
                'location' => 'Ouidah',
                'region' => 'Atlantique',
                'traditions' => 'Musique, danse, mode, gastronomie',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 14,
            ],
            [
                'name' => 'Fête des Ignames',
                'description' => 'Célébration de la récolte des ignames, aliment sacré dans la culture béninoise.',
                'type' => 'celebration',
                'event_date' => Carbon::create(now()->year, 8, 15),
                'location' => 'Savalou',
                'region' => 'Collines',
                'ethnic_origin' => 'Mahi',
                'traditions' => 'Récolte, danses, festins communautaires',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 7,
            ],
            [
                'name' => 'Festival Gelede',
                'description' => 'Célébration du patrimoine Gelede, inscrit au patrimoine de l\'UNESCO.',
                'type' => 'festival',
                'event_date' => Carbon::create(now()->year, 3, 20),
                'location' => 'Pobè',
                'region' => 'Plateau',
                'ethnic_origin' => 'Yoruba',
                'traditions' => 'Masques gelede, chants, danses rituelles',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 7,
            ],
            [
                'name' => 'Fête des Egungun',
                'description' => 'Cérémonie en l\'honneur des ancêtres avec les masques Egungun.',
                'type' => 'ceremony',
                'event_date' => Carbon::create(now()->year, 6, 1),
                'location' => 'Porto-Novo',
                'region' => 'Ouémé',
                'ethnic_origin' => 'Yoruba',
                'traditions' => 'Masques ancestraux, rituels, bénédictions',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 7,
            ],
            [
                'name' => 'Festival Villes et Arts',
                'description' => 'Festival urbain mettant en valeur l\'art de rue et la culture contemporaine.',
                'type' => 'festival',
                'event_date' => Carbon::create(now()->year, 11, 5),
                'location' => 'Cotonou',
                'region' => 'Littoral',
                'traditions' => 'Street art, performances, expositions',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'notification_days_before' => 10,
            ],
        ];

        foreach ($events as $eventData) {
            // Ajuster la date si elle est déjà passée
            if ($eventData['event_date']->isPast()) {
                $eventData['event_date'] = $eventData['event_date']->addYear();
            }

            CulturalEvent::create($eventData);
        }
    }
}
