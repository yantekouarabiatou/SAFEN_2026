<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatLog;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'language' => 'in:fr,en,fon'
        ]);

        $message = $request->input('message');
        $language = $request->input('language', 'fr');

        // Log la question
        ChatLog::create([
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'message' => $message,
            'language' => $language,
        ]);

        // Préparer le prompt pour OpenAI
        $systemPrompt = "Tu es Anansi, l'assistant IA d'AFRI-HERITAGE, la marketplace de l'artisanat béninois.

        Ton rôle :
        - Aider les utilisateurs à découvrir la culture béninoise
        - Recommander des artisans et produits de notre plateforme
        - Expliquer les significations culturelles des objets artisanaux
        - Traduire des termes en français/anglais/fon
        - Guider dans la navigation du site

        Ton de voix : chaleureux, pédagogue, fier de la culture béninoise

        Base de connaissances sur le Bénin :
        - Ethnies principales : Fon, Yoruba, Bariba, Dendi, Somba, Peulh
        - Villes principales : Cotonou, Porto-Novo, Parakou, Abomey, Ouidah, Natitingou
        - Artisanat : masques Guèlèdè, sculptures en bois, poteries, tissages, bijoux en perles
        - Gastronomie : Amiwo, Akassa, Tchoukoutou, Yovo doko, Sodabi

        Réponds toujours en {$language} (sauf si l'utilisateur demande une autre langue).
        Sois concis (max 150 mots) et utile.

        Si l'utilisateur cherche un artisan ou produit, oriente-le vers notre catalogue en ligne.
        Si la question n'est pas liée au Bénin ou à l'artisanat, explique poliment que tu es spécialisé dans la culture béninoise.";

        try {
            // Appel à l'API OpenAI (vous aurez besoin d'une clé API)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'max_tokens' => 300,
                'temperature' => 0.8,
            ]);

            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Désolé, je ne peux pas répondre pour le moment.';

            // Log la réponse
            ChatLog::where('session_id', session()->getId())
                ->latest()
                ->first()
                ->update(['response' => $reply]);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            // En cas d'erreur, renvoyer une réponse par défaut
            return response()->json([
                'reply' => "Je suis désolé, je rencontre des difficultés techniques.
                Pour trouver des artisans ou produits, vous pouvez directement explorer nos catégories :
                • Artisans & Services
                • Gastronomie béninoise
                • Marketplace d'artisanat"
            ]);
        }
    }
}
