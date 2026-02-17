<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    // Coordonnées du point de départ (votre entrepôt/boutique principale)
    const WAREHOUSE_LAT = 6.3654; // Cotonou - À ajuster selon votre localisation
    const WAREHOUSE_LNG = 2.4183;

    // Villes principales du Bénin avec leurs coordonnées
    private static $cities = [
        'Cotonou' => ['lat' => 6.3654, 'lng' => 2.4183],
        'Porto-Novo' => ['lat' => 6.4969, 'lng' => 2.6289],
        'Parakou' => ['lat' => 9.3372, 'lng' => 2.6103],
        'Abomey-Calavi' => ['lat' => 6.4489, 'lng' => 2.3553],
        'Djougou' => ['lat' => 9.7085, 'lng' => 1.6660],
        'Bohicon' => ['lat' => 7.1781, 'lng' => 2.0667],
        'Kandi' => ['lat' => 11.1342, 'lng' => 2.9386],
        'Lokossa' => ['lat' => 6.6389, 'lng' => 1.7167],
        'Ouidah' => ['lat' => 6.3636, 'lng' => 2.0852],
        'Natitingou' => ['lat' => 10.3042, 'lng' => 1.3794],
        'Abomey' => ['lat' => 7.1828, 'lng' => 1.9914],
        'Savalou' => ['lat' => 7.9281, 'lng' => 1.9756],
    ];

    /**
     * Calculer la distance en km entre l'entrepôt et la destination
     */
    public static function calculateDistance($destinationCity)
    {
        if (!isset(self::$cities[$destinationCity])) {
            // Ville inconnue, utiliser une distance moyenne
            return 50;
        }

        $destination = self::$cities[$destinationCity];
        
        // Formule de Haversine pour calculer la distance
        $earthRadius = 6371; // km

        $latFrom = deg2rad(self::WAREHOUSE_LAT);
        $lonFrom = deg2rad(self::WAREHOUSE_LNG);
        $latTo = deg2rad($destination['lat']);
        $lonTo = deg2rad($destination['lng']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Calculer les frais de livraison basés sur la distance
     */
    public static function calculateDeliveryFee($destinationCity, $orderTotal = 0)
    {
        $distance = self::calculateDistance($destinationCity);
        
        // Tarification progressive
        if ($distance <= 10) {
            $baseFee = 1000; // 1000 FCFA pour moins de 10km
        } elseif ($distance <= 30) {
            $baseFee = 1500; // 1500 FCFA pour 10-30km
        } elseif ($distance <= 100) {
            $baseFee = 2500; // 2500 FCFA pour 30-100km
        } elseif ($distance <= 200) {
            $baseFee = 4000; // 4000 FCFA pour 100-200km
        } else {
            $baseFee = 6000; // 6000 FCFA pour plus de 200km
        }

        // Livraison gratuite pour les commandes supérieures à 50 000 FCFA
        if ($orderTotal >= 50000) {
            return 0;
        }

        // Réduction de 50% pour les commandes supérieures à 25 000 FCFA
        if ($orderTotal >= 25000) {
            return $baseFee / 2;
        }

        return $baseFee;
    }

    /**
     * Obtenir les détails de livraison
     */
    public static function getDeliveryDetails($destinationCity, $orderTotal = 0)
    {
        $distance = self::calculateDistance($destinationCity);
        $fee = self::calculateDeliveryFee($destinationCity, $orderTotal);
        
        // Estimation du délai de livraison
        if ($distance <= 30) {
            $estimatedDays = '1-2 jours';
        } elseif ($distance <= 100) {
            $estimatedDays = '2-3 jours';
        } elseif ($distance <= 200) {
            $estimatedDays = '3-5 jours';
        } else {
            $estimatedDays = '5-7 jours';
        }

        return [
            'distance' => $distance,
            'fee' => $fee,
            'formatted_fee' => number_format($fee, 0, ',', ' ') . ' FCFA',
            'estimated_delivery' => $estimatedDays,
            'free_delivery_threshold' => 50000,
            'discount_threshold' => 25000
        ];
    }
}