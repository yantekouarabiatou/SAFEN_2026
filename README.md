# TOTCHEMEGNON - Plateforme Artisanale et Gastronomique du Bénin

TOTCHEMEGNON est une marketplace web innovante qui connecte les artisans, les vendeurs de nourriture et les clients au Bénin. Propulsée par l'intelligence artificielle, elle valorise le patrimoine culturel béninois en offrant des descriptions enrichies, des audios de prononciation locale et un assistant virtuel (Anansi) pour guider les utilisateurs.

## Fonctionnalités principales

- **Artisans & Services** : Inscription, profils publics, portfolio, géolocalisation, notation, contact direct.
- **Gastronomie béninoise** : Catalogue de plats avec descriptions culturelles, audio des noms locaux, géolocalisation des points de vente.
- **Arts & Artisanat (Marketplace)** : Vente d'objets artisanaux, fiches produits enrichies par l'IA, panier et wishlist.
- **Intelligence Artificielle** :
  - Génération automatique de descriptions culturelles (OpenAI).
  - Traduction multilingue (français, anglais, fon).
  - Audio des noms locaux (ElevenLabs / Google TTS).
  - Chatbot Anansi pour assistance et découverte culturelle.
  - Recommandations personnalisées.
- **Dashboard Administrateur** : Gestion des utilisateurs, modération, statistiques, logs.
- **Recherche & Filtres** : Recherche globale, filtres avancés, géolocalisation.
- **Interface multilingue** : Français, Anglais, Fon (partiel).

## Technologies utilisées

- **Backend** : Laravel 12, PHP 8.2+, MySQL 8.0, Redis (cache, queues)
- **Frontend** : Blade, TailwindCSS 3, Alpine.js, Google Maps API, Cloudinary
- **IA & Services** : OpenAI API (GPT-4 Turbo), ElevenLabs / Google TTS, Cloudinary
- **Authentification** : Laravel Breeze
- **Déploiement** : Railway.app / Vercel, Git

## Prérequis

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL >= 8.0
- Redis (optionnel)
- Comptes API : OpenAI, ElevenLabs (ou Google Cloud), Google Maps, Cloudinary

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-utilisateur/totchemegnon.git
   cd totchemegnon
   composer install
   php artisan migrate --seed
   php artisan serve
   php artisan key:generate
   php artisan storage:link