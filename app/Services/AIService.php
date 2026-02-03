<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Dish;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AIService
{
    protected $openaiApiKey;
    protected $elevenlabsApiKey;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.key');
        $this->elevenlabsApiKey = config('services.elevenlabs.key');
    }

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

Ton : chaleureux, éducatif, engageant
Public : clients internationaux curieux de culture";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert en artisanat béninois.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return null;
    }

    public function translateText($text, $targetLang, $sourceLang = 'fr')
    {
        $langNames = [
            'fr' => 'français',
            'en' => 'anglais',
            'fon' => 'Fon (langue du Bénin)',
            'yoruba' => 'Yoruba'
        ];

        $prompt = "Traduis ce texte en {$langNames[$targetLang]} de manière naturelle et culturellement appropriée :

Texte source ({$langNames[$sourceLang]}) :
{$text}

Contexte : Description d'un produit artisanal béninois pour une marketplace en ligne.

Retourne UNIQUEMENT la traduction, sans explications.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 800,
            'temperature' => 0.3,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return $text;
    }

    public function generateAudio($text, $language = 'fr', $voiceId = null)
    {
        $voiceIds = [
            'fr' => 'ErXwobaYiN019PkySvjV', // French voice
            'en' => 'EXAVITQu4vr4xnSDxMaL', // English voice
            'fon' => $voiceId ?? '21m00Tcm4TlvDq8ikWAM' // Fon voice or custom
        ];

        $voiceId = $voiceIds[$language] ?? $voiceIds['fr'];

        $response = Http::withHeaders([
            'xi-api-key' => $this->elevenlabsApiKey,
            'Content-Type' => 'application/json',
        ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
            'text' => $text,
            'model_id' => 'eleven_multilingual_v2',
            'voice_settings' => [
                'stability' => 0.5,
                'similarity_boost' => 0.75,
                'style' => 0.3,
                'use_speaker_boost' => true
            ]
        ]);

        if ($response->successful()) {
            $filename = 'audio/' . uniqid() . '.mp3';
            Storage::disk('public')->put($filename, $response->body());

            return Storage::url($filename);
        }

        // Fallback to Google TTS
        return $this->generateGoogleAudio($text, $language);
    }

    protected function generateGoogleAudio($text, $languageCode = 'fr-FR')
    {
        // Implementation using Google Cloud TTS
        // Note: Requires google/cloud-text-to-speech package
        return null;
    }

    public function chatAnansi($message, $history = [])
    {
        $systemPrompt = "Tu es Anansi, l'assistant IA d'AFRI-HERITAGE, la marketplace de l'artisanat béninois.

Ton rôle :
- Aider les utilisateurs à découvrir la culture béninoise
- Recommander des artisans et produits
- Expliquer les significations culturelles
- Traduire des termes en Fon/Yoruba
- Guider dans la navigation du site

Ton de voix : chaleureux, pédagogue, fier de la culture béninoise

Base de connaissances :
- Le Bénin compte 12 départements
- Principales ethnies : Fon, Yoruba, Bariba, Dendi, Somba, Peulh
- Artisanat emblématique : masques Guèlèdè, bronze, tissage, poterie
- Plats typiques : Amiwo, Akassa, Yovo doko, Tchoukoutou

Réponds toujours en français sauf si l'utilisateur écrit en anglais.
Sois concis (max 150 mots par réponse).";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Add conversation history
        foreach (array_slice($history, -10) as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        // Add new message
        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => $messages,
            'max_tokens' => 300,
            'temperature' => 0.8,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return "Désolé, je ne peux pas répondre pour le moment. Veuillez réessayer plus tard.";
    }
}
