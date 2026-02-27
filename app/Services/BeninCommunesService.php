<?php

namespace App\Services;

class BeninCommunesService
{
    /**
     * Retourne toutes les communes groupées par département
     */
    public static function getAllGrouped(): array
    {
        return [
            'Alibori' => [
                'Banikoara', 'Gogounou', 'Kandi', 'Karimama',
                'Malanville', 'Ségbana',
            ],
            'Atacora' => [
                'Boukoumbé', 'Cobly', 'Kérou', 'Kouandé',
                'Matéri', 'Natitingou', 'Péhunco', 'Tanguiéta', 'Toucountouna',
            ],
            'Atlantique' => [
                'Abomey-Calavi', 'Allada', 'Kpomassè', 'Missérété',
                'Ouidah', 'Sô-Ava', 'Toffo', 'Tori-Bossito', 'Zè',
            ],
            'Borgou' => [
                'Bembèrèkè', 'Kalalé', 'N\'Dali', 'Nikki',
                'Parakou', 'Pèrèrè', 'Sinendé', 'Tchaourou',
            ],
            'Collines' => [
                'Bantè', 'Dassa-Zoumè', 'Glazoué', 'Ouèssè',
                'Savalou', 'Savè',
            ],
            'Couffo' => [
                'Aplahoué', 'Djakotomey', 'Dogbo', 'Klouékanmè',
                'Lalo', 'Toviklin',
            ],
            'Donga' => [
                'Bassila', 'Copargo', 'Djougou', 'Ouaké',
            ],
            'Littoral' => [
                'Cotonou',
            ],
            'Mono' => [
                'Athiémé', 'Bopa', 'Comè', 'Grand-Popo',
                'Houéyogbé', 'Lokossa',
            ],
            'Ouémé' => [
                'Adjohoun', 'Aguégués', 'Akpro-Missérété', 'Avrankou',
                'Bonou', 'Dangbo', 'Porto-Novo', 'Sèmè-Podji',
            ],
            'Plateau' => [
                'Adja-Ouèrè', 'Ifangni', 'Kétou', 'Pobè', 'Sakété',
            ],
            'Zou' => [
                'Abomey', 'Agbangnizoun', 'Bohicon', 'Covè',
                'Djidja', 'Ouinhi', 'Za-Kpota', 'Zangnanado', 'Zogbodomey',
            ],
        ];
    }

    /**
     * Retourne toutes les communes à plat (pour select simple)
     */
    public static function getAll(): array
    {
        $all = [];
        foreach (self::getAllGrouped() as $department => $communes) {
            foreach ($communes as $commune) {
                $all[] = $commune;
            }
        }
        sort($all);
        return $all;
    }

    /**
     * Retourne le format pour Select2 avec groupes
     * [['text' => 'Département', 'children' => [['id' => 'Ville', 'text' => 'Ville'], ...]]]
     */
    public static function getForSelect2(): array
    {
        $result = [];
        foreach (self::getAllGrouped() as $department => $communes) {
            $children = array_map(fn($c) => ['id' => $c, 'text' => $c], $communes);
            $result[] = [
                'text'     => $department,
                'children' => $children,
            ];
        }
        return $result;
    }

    /**
     * Frais de livraison par commune
     */
    public static function getDeliveryFee(string $commune): int
    {
        // Cotonou = tarif de base
        if ($commune === 'Cotonou') return 1500;

        // Zone 1 — Grand Cotonou
        $zone1 = ['Abomey-Calavi', 'Sèmè-Podji', 'Porto-Novo', 'Ouidah', 'Allada'];
        if (in_array($commune, $zone1)) return 2500;

        // Zone 2 — Sud Bénin
        $zone2 = [
            'Bohicon', 'Abomey', 'Lokossa', 'Grand-Popo', 'Comè',
            'Kétou', 'Pobè', 'Sakété', 'Adjohoun', 'Dangbo',
        ];
        if (in_array($commune, $zone2)) return 4000;

        // Zone 3 — Centre
        $zone3 = ['Parakou', 'Dassa-Zoumè', 'Savalou', 'Savè', 'Djougou'];
        if (in_array($commune, $zone3)) return 6000;

        // Zone 4 — Nord Bénin
        $zone4 = ['Kandi', 'Natitingou', 'Malanville', 'Nikki', 'Banikoara'];
        if (in_array($commune, $zone4)) return 8000;

        // Toutes les autres communes
        return 5000;
    }
}
