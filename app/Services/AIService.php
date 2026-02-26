<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Dish;
use App\Models\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AIService
{
    protected $openaiApiKey;
    protected $elevenlabsApiKey;

    public function __construct()
    {
        $this->openaiApiKey     = config('services.openai.key');
        $this->elevenlabsApiKey = config('services.elevenlabs.key');
    }

    // =========================================================================
    //  CHATBOT ANANSI — via Groq (gratuit)
    // =========================================================================

    public function chatAnansi(string $message, array $history = [], string $language = 'fr'): string
    {
        $apiKey = config('services.groq.key');

        // Construire le system prompt enrichi avec données réelles
        $systemPrompt = $this->buildRichSystemPrompt($language);

        // Construire les messages avec historique
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach (array_slice($history, -10) as $entry) {
            $messages[] = ['role' => 'user',      'content' => $entry['user'] ?? ''];
            $messages[] = ['role' => 'assistant', 'content' => $entry['bot']  ?? ''];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $data = json_encode([
            'model'       => 'llama-3.3-70b-versatile',
            'messages'    => $messages,
            'max_tokens'  => 500,
            'temperature' => 0.8,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            \Log::error('Groq CURL error: ' . $curlError);
            return $this->fallback($language);
        }

        $json = json_decode($response, true);

        if ($httpCode === 200) {
            return $json['choices'][0]['message']['content'] ?? $this->fallback($language);
        }

        \Log::error('Groq API error', ['status' => $httpCode, 'body' => $json]);
        return $this->fallback($language);
    }

    // =========================================================================
    //  SYSTEM PROMPT ENRICHI AVEC DONNÉES RÉELLES
    // =========================================================================

    private function buildRichSystemPrompt(string $language): string
    {
        // Récupérer les données réelles depuis la BDD (mis en cache 1h)
        $context = Cache::remember('anansi_context', 3600, function () {
            return $this->buildDatabaseContext();
        });

        if ($language === 'en') {
            return "You are Anansi, the AI cultural assistant of TOTCHEMEGNON, the Beninese artisan marketplace.
Named after Anansi the spider trickster from African mythology.

YOUR ROLE:
- Help users find artisans, products, and gastronomy on the platform
- Explain Beninese cultural traditions and history
- Guide navigation on the site
- Answer in English if the user writes in English, otherwise in French

TONE: Warm, pedagogical, proud of Beninese culture. Max 150 words per response.
Use markdown: **bold** for names/titles, bullet points for lists.

=== PLATFORM DATA ===
{$context}

If asked about something not in the data, answer from your general knowledge about Benin.";
        }

        return "Tu es **Anansi**, l'assistant culturel IA d'**TOTCHEMEGNON**, la marketplace de l'artisanat béninois.
Nommé d'après Anansi l'araignée, célèbre trickster de la mythologie africaine.

TON RÔLE :
- Aider les utilisateurs à trouver des artisans, produits et plats sur la plateforme
- Expliquer les traditions culturelles et l'histoire du Bénin
- Guider la navigation sur le site
- Toujours répondre en français sauf si l'utilisateur écrit en anglais

TON : Chaleureux, pédagogue, fier de la culture béninoise. Max 150 mots par réponse.
Utilise le markdown : **gras** pour les noms/titres, listes à puces pour énumérer.

=== DONNÉES RÉELLES DE LA PLATEFORME ===
{$context}

Si on te pose une question non couverte par les données, réponds depuis tes connaissances générales sur le Bénin.";
    }

    /**
     * Construit un contexte textuel depuis la base de données
     */
    private function buildDatabaseContext(): string
    {
        $context = "";

        // --- Artisans ---
        try {
            $artisans = Artisan::where('visible', true)
                ->with('user')
                ->take(15)
                ->get();

            if ($artisans->isNotEmpty()) {
                $context .= "ARTISANS DISPONIBLES (" . $artisans->count() . ") :\n";
                foreach ($artisans as $a) {
                    $name     = $a->user->name ?? 'Inconnu';
                    $craft    = $a->craft_label ?? $a->craft ?? 'Artisan';
                    $location = $a->location ?? $a->city ?? 'Bénin';
                    $rating   = $a->rating_avg > 0 ? " ⭐{$a->rating_avg}/5" : "";
                    $context .= "- {$name} : {$craft}, {$location}{$rating}\n";
                }
                $context .= "\n";
            }
        } catch (\Exception $e) {
            $context .= "ARTISANS : données non disponibles\n\n";
        }

        // --- Plats ---
        try {
            $dishes = Dish::take(10)->get();

            if ($dishes->isNotEmpty()) {
                $context .= "PLATS BÉNINOIS RÉFÉRENCÉS (" . $dishes->count() . ") :\n";
                foreach ($dishes as $d) {
                    $name   = $d->name ?? '';
                    $local  = $d->name_local ? " ({$d->name_local})" : "";
                    $origin = $d->ethnic_origin ?? '';
                    $region = $d->region ?? '';
                    $context .= "- {$name}{$local} : origine {$origin}, {$region}\n";
                }
                $context .= "\n";
            }
        } catch (\Exception $e) {
            $context .= "PLATS : Amiwo, Akassa, Tchoucoutou, Atassi, Wagashi, Yovo doko\n\n";
        }

        // --- Produits ---
        try {
            $products = Product::take(10)->get();

            if ($products->isNotEmpty()) {
                $context .= "PRODUITS ARTISANAUX RÉFÉRENCÉS (" . $products->count() . ") :\n";
                foreach ($products as $p) {
                    $name     = $p->name ?? '';
                    $category = $p->category_label ?? $p->category ?? '';
                    $origin   = $p->ethnic_origin ?? '';
                    $price    = $p->formatted_price ?? '';
                    $context .= "- {$name} : {$category}, origine {$origin}, {$price}\n";
                }
                $context .= "\n";
            }
        } catch (\Exception $e) {
            $context .= "PRODUITS : masques Guèlèdè, bronzes, tissages, poteries, bijoux\n\n";
        }

        // --- Connaissances culturelles fixes ---
        $context .= "CULTURE BÉNINOISE :
- 12 départements : Alibori, Atacora, Atlantique, Borgou, Collines, Couffo, Donga, Littoral, Mono, Ouémé, Plateau, Zou
- Principales ethnies : Fon, Yoruba, Bariba, Dendi, Somba (Otamari), Peulh (Fulani)
- Religions : Vaudou (animisme béninois, reconnu officiellement), Christianisme, Islam
- Artisanat emblématique : masques Guèlèdè (patrimoine UNESCO), bronzes du Dahomey, tissage Fon, poterie, sculptures en bois
- Traditions : Zangbeto (gardiens de nuit), Egungun (masques ancêtres Yoruba), Gelede (Fon/Yoruba)
- Musique : Tchinkoumé, Agbadja, Zinli
- Fête nationale : 1er août (Indépendance du Dahomey/Bénin, 1960)
- Capitale politique : Porto-Novo | Capitale économique : Cotonou
";

        return $context;
    }

    private function fallback(string $language): string
    {
        return $language === 'en'
            ? "Sorry, I'm temporarily unavailable. Please browse our [artisans](/artisans) or [gastronomy](/gastronomie) sections directly."
            : "Désolé, je suis temporairement indisponible. Parcourez directement nos sections [artisans](/artisans) ou [gastronomie](/gastronomie).";
    }

    // =========================================================================
    //  VIDER LE CACHE DU CONTEXTE (à appeler quand les données changent)
    // =========================================================================

    public static function clearContextCache(): void
    {
        Cache::forget('anansi_context');
    }

    // =========================================================================
    //  AUTRES MÉTHODES EXISTANTES (inchangées)
    // =========================================================================

    public function generateCulturalDescription(Product $product)
    {
        $prompt = "Tu es un expert en artisanat et culture béninoise.
Génère une description culturelle captivante pour ce produit :
Nom : {$product->name}
Catégorie : {$product->category_label}
Ethnie d'origine : {$product->ethnic_origin}
Matériaux : " . implode(', ', $product->materials ?? []) . "

Inclus dans ta réponse (200-250 mots) :
1. L'histoire et origine de cet objet
2. Sa signification symbolique ou spirituelle
3. Son usage traditionnel
4. Les techniques de fabrication
5. Son importance dans la culture béninoise

Ton : chaleureux, éducatif, engageant.";

        // Utiliser Groq au lieu d'OpenAI
        return $this->askGroq($prompt, 600);
    }

    public function translateText(string $text, string $targetLang, string $sourceLang = 'fr'): string
    {
        $langNames = [
            'fr'     => 'français',
            'en'     => 'anglais',
            'fon'    => 'Fon (langue du Bénin)',
            'yoruba' => 'Yoruba',
        ];

        $prompt = "Traduis ce texte en {$langNames[$targetLang]} de manière naturelle et culturellement appropriée.
Contexte : Description d'un produit artisanal béninois.
Retourne UNIQUEMENT la traduction, sans explications.

Texte source ({$langNames[$sourceLang]}) :
{$text}";

        return $this->askGroq($prompt, 800) ?? $text;
    }

    /**
     * Méthode générique pour appeler Groq
     */
    private function askGroq(string $prompt, int $maxTokens = 400): ?string
    {
        $apiKey = config('services.groq.key');

        $data = json_encode([
            'model'      => 'llama-3.3-70b-versatile',
            'messages'   => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => $maxTokens,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $json = json_decode($response, true);
            return $json['choices'][0]['message']['content'] ?? null;
        }

        return null;
    }

    public function generateAudio(string $text, string $language = 'fr', ?string $voiceId = null): ?string
    {
        $voiceIds = [
            'fr'  => 'ErXwobaYiN019PkySvjV',
            'en'  => 'EXAVITQu4vr4xnSDxMaL',
            'fon' => $voiceId ?? '21m00Tcm4TlvDq8ikWAM',
        ];

        $voice = $voiceIds[$language] ?? $voiceIds['fr'];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://api.elevenlabs.io/v1/text-to-speech/{$voice}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode([
                'text'           => $text,
                'model_id'       => 'eleven_multilingual_v2',
                'voice_settings' => ['stability' => 0.5, 'similarity_boost' => 0.75],
            ]),
            CURLOPT_HTTPHEADER     => [
                'xi-api-key: ' . $this->elevenlabsApiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $filename = 'audio/' . uniqid() . '.mp3';
            Storage::disk('public')->put($filename, $response);
            return Storage::url($filename);
        }

        return null;
    }
}