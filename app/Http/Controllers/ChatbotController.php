<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\AIService;

class ChatbotController extends Controller
{
    /**
     * Envoyer un message au chatbot et obtenir une réponse de l'IA
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'language' => 'nullable|string|in:fr,en',
        ]);

        $userMessage = $request->message;
        $language = $request->language ?? 'fr';

        try {
            // 1. Analyser le type de question
            $intent = $this->detectIntent($userMessage);

            // 2. Générer la réponse selon l'intent
            $reply = match ($intent['type']) {
                'artisan_search' => $this->handleArtisanSearch($userMessage, $intent),
                'artisan_contact' => $this->handleArtisanContact($intent['artisan_number']),
                'product_info' => $this->handleProductInfo($userMessage, $intent),
                'dish_info' => $this->handleDishInfo($userMessage, $intent),
                'cultural_info' => $this->handleCulturalInfo($userMessage, $language),
                'general' => $this->handleGeneralQuestion($userMessage, $language),
                default => $this->handleGeneralQuestion($userMessage, $language),
            };

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'intent' => $intent['type'],
            ]);

            // Sauvegarder l'historique de la conversation
            $this->saveConversationHistory($userMessage, $reply);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'reply' => $language === 'fr'
                    ? "Désolé, je rencontre une difficulté. Pouvez-vous reformuler votre question ?"
                    : "Sorry, I'm having trouble. Could you rephrase your question?",
            ], 500);
        }
    }

    /**
     * Détecter l'intention de l'utilisateur
     */
    private function detectIntent(string $message): array
    {
        $message = strtolower($message);

        // Recherche d'artisan par numéro (ex: "contacte le 2", "le numéro 3")
        if (preg_match('/(?:contacte|appelle|numéro|le)\s+(\d+)/i', $message, $matches)) {
            return [
                'type' => 'artisan_contact',
                'artisan_number' => (int) $matches[1],
            ];
        }

        // Recherche d'artisan par numéro avec "artisan" (ex: "l'artisan 2")
        if (preg_match('/artisan\s+(\d+)/i', $message, $matches)) {
            return [
                'type' => 'artisan_contact',
                'artisan_number' => (int) $matches[1],
            ];
        }

        // Recherche d'artisan - mots-clés étendus
        $artisanKeywords = [
            'tailleur', 'mécanicien', 'coiffeur', 'menuisier', 'artisan', 'trouve', 'cherche', 'besoin',
            'réparer', 'confection', 'couture', 'tissage', 'sculpture', 'bijou', 'potier', 'forgeron',
            'tanneur', 'corroyeur', 'musicien', 'vulcanisateur', 'plombier', 'électricien', 'peintre',
            'maçon', 'charpentier', 'serrurier', 'vitrier', 'carreleur', 'professionnel', 'spécialiste'
        ];
        foreach ($artisanKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return [
                    'type' => 'artisan_search',
                    'craft' => $this->extractCraft($message),
                    'location' => $this->extractLocation($message),
                ];
            }
        }

        // Information sur un produit
        $productKeywords = ['masque', 'sculpture', 'tissu', 'bijou', 'acheter', 'prix', 'produit'];
        foreach ($productKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return [
                    'type' => 'product_info',
                    'product' => $this->extractProductName($message),
                ];
            }
        }

        // Information sur un plat
        $dishKeywords = ['amiwo', 'akassa','tchoukoutou','tchapkalo','ablo','attassi','tamtam','kente' ,'plat', 'cuisine', 'manger', 'recette', 'gastronomie'];
        foreach ($dishKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return [
                    'type' => 'dish_info',
                    'dish' => $this->extractDishName($message),
                ];
            }
        }

        // Information culturelle
        $cultureKeywords = ['guèlèdè', 'vaudou', 'culture','tamtam', 'histoire', 'tradition', 'explique', 'c\'est quoi'];
        foreach ($cultureKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return ['type' => 'cultural_info'];
            }
        }

        return ['type' => 'general'];
    }

    /**
     * Gérer la recherche d'artisan
     */
    private function handleArtisanSearch(string $message, array $intent): string
    {
        $craft = $intent['craft'] ?? null;
        $location = $intent['location'] ?? null;

        // Rechercher dans la base de données
        $query = \App\Models\Artisan::query()->where('visible', true);

        if ($craft) {
            $query->where('craft', 'like', "%{$craft}%");
        }

        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                    ->orWhere('neighborhood', 'like', "%{$location}%");
            });
        }

        $artisans = $query->take(3)->get();

        if ($artisans->isEmpty()) {
            return "Je n'ai pas trouvé d'artisan correspondant à votre recherche. Voulez-vous élargir votre zone de recherche ou essayer un autre métier ?";
        }

        $response = "J'ai trouvé " . $artisans->count() . " artisan(s) pour vous :\n\n";

        foreach ($artisans as $artisan) {
            $response .= "👤 **{$artisan->user->name}**\n";
            $response .= "🔨 {$artisan->craft_label}\n";
            $response .= "📍 {$artisan->location}\n";
            if ($artisan->rating_avg > 0) {
                $response .= "⭐ {$artisan->rating_avg}/5 ({$artisan->rating_count} avis)\n";
            }
            $response .= "📞 [Contacter](" . route('artisans.show', $artisan) . ")\n\n";
        }

        $response .= "Voulez-vous plus d'informations sur l'un d'eux ?";

        return $response;
    }

    /**
     * Gérer les informations sur un produit
     */
    private function handleProductInfo(string $message, array $intent): string
    {
        $productName = $intent['product'] ?? null;

        if ($productName) {
            $product = \App\Models\Product::where('name', 'like', "%{$productName}%")
                ->orWhere('name_local', 'like', "%{$productName}%")
                ->first();

            if ($product) {
                $response = "📦 **{$product->name}**";
                if ($product->name_local) {
                    $response .= " ({$product->name_local})";
                }
                $response .= "\n\n";

                if ($product->description_cultural) {
                    $response .= "🎨 **Signification culturelle :**\n";
                    $response .= substr($product->description_cultural, 0, 200) . "...\n\n";
                }

                $response .= "💰 Prix : {$product->formatted_price}\n";
                $response .= "👤 Artisan : {$product->artisan->user->name}\n";
                $response .= "📍 Origine : {$product->ethnic_origin}\n\n";
                $response .= "[Voir le produit](" . route('products.show', $product) . ")";

                return $response;
            }
        }

        return "Je peux vous aider à trouver des produits artisanaux ! Recherchez-vous un type particulier : masques, sculptures, tissus, bijoux ?";
    }

    /**
     * Gérer les informations sur un plat
     */
    private function handleDishInfo(string $message, array $intent): string
    {
        $dishName = $intent['dish'] ?? null;

        if ($dishName) {
            $dish = \App\Models\Dish::where('name', 'like', "%{$dishName}%")
                ->orWhere('name_local', 'like', "%{$dishName}%")
                ->first();

            if ($dish) {
                $response = "🍲 **{$dish->name}**";
                if ($dish->name_local) {
                    $response .= " ({$dish->name_local})";
                }
                $response .= "\n\n";

                if ($dish->description) {
                    $response .= substr($dish->description, 0, 250) . "...\n\n";
                }

                $response .= "🌍 Origine : {$dish->ethnic_origin} - {$dish->region}\n";

                if ($dish->ingredients && count($dish->ingredients) > 0) {
                    $response .= "🥘 Ingrédients principaux : " . implode(', ', array_slice($dish->ingredients, 0, 5)) . "\n";
                }

                if ($dish->occasions) {
                    $response .= "🎉 Occasions : {$dish->occasions}\n";
                }

                $response .= "\n[Voir la recette complète](" . route('gastronomie.show', $dish) . ")";

                return $response;
            }
        }

        return "Je peux vous parler de la gastronomie béninoise ! Voulez-vous découvrir des plats comme l'Amiwo, l'Akassa, le Tchoucoutou ou l'Atassi ?";
    }

    /**
     * Gérer les questions culturelles avec l'API Claude
     */
    private function handleCulturalInfo(string $message, string $language): string
    {
        $aiService = new AIService();
        return $aiService->chatAnansi($message, []);
    }

    /**
     * Gérer les questions générales
     */
    private function handleGeneralQuestion(string $message, string $language): string
    {
        $aiService = new AIService();
        return $aiService->chatAnansi($message, []);
    }

    private function askOpenAI(string $message, string $language, string $context): string
    {
        $apiKey = config('services.openai.key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => config('services.openai.model', 'gpt-4-turbo-preview'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->buildSystemPrompt($context, $language)
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'max_tokens' => config('services.openai.max_tokens', 500),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? $this->getFallbackResponse($message, $language);
        }

        return $this->getFallbackResponse($message, $language);
    }

    /**
     * Construire le prompt système pour Claude
     */
    private function buildSystemPrompt(string $context, string $language): string
    {
        $basePrompt = "Tu es Anansi, un assistant culturel IA spécialisé dans la culture béninoise. ";
        $basePrompt .= "Tu es nommé d'après Anansi l'araignée, le célèbre trickster de la mythologie africaine. ";
        $basePrompt .= "Tu es chaleureux, pédagogue et passionné par le partage de la culture béninoise. ";

        if ($language === 'en') {
            $basePrompt = "You are Anansi, a cultural AI assistant specializing in Beninese culture. ";
            $basePrompt .= "You are named after Anansi the spider, the famous trickster from African mythology. ";
            $basePrompt .= "You are warm, pedagogical, and passionate about sharing Beninese culture. ";
        }

        $contextualPrompts = [
            'cultural' => $language === 'fr'
                ? "Réponds aux questions sur la culture, l'histoire et les traditions du Bénin. Sois précis, informatif et engageant. Limite tes réponses à 150 mots maximum."
                : "Answer questions about Beninese culture, history and traditions. Be precise, informative and engaging. Limit responses to 150 words maximum.",

            'general' => $language === 'fr'
                ? "Tu aides les utilisateurs de la plateforme AFRI-HERITAGE à naviguer, trouver des artisans, découvrir des produits artisanaux et la gastronomie béninoise. Sois concis et utile. Maximum 150 mots."
                : "You help AFRI-HERITAGE platform users navigate, find artisans, discover craft products and Beninese gastronomy. Be concise and helpful. Maximum 150 words.",
        ];

        return $basePrompt . $contextualPrompts[$context];
    }

    /**
     * Réponse de secours si l'API est indisponible
     */
    private function getFallbackResponse(string $message, string $language): string
    {
        $fallbacks = [
            'fr' => [
                "Je peux vous aider à découvrir le Bénin ! Posez-moi des questions sur les artisans, les produits artisanaux ou la gastronomie.",
                "Intéressant ! Je peux vous en dire plus sur la culture béninoise. Que voulez-vous savoir exactement ?",
                "C'est une bonne question ! Puis-je vous orienter vers notre section [Artisans](/artisans) ou [Gastronomie](/gastronomie) ?",
            ],
            'en' => [
                "I can help you discover Benin! Ask me about artisans, handicrafts or gastronomy.",
                "Interesting! I can tell you more about Beninese culture. What would you like to know exactly?",
                "Good question! Can I direct you to our [Artisans](/artisans) or [Gastronomy](/gastronomie) section?",
            ],
        ];

        return $fallbacks[$language][array_rand($fallbacks[$language])];
    }

    /**
     * Extraire le métier mentionné dans le message
     */
    private function extractCraft(string $message): ?string
    {
        $crafts = [
            // Métiers traditionnels africains/béninois
            'tailleur' => 'couturier',
            'couturier' => 'couturier',
            'couture' => 'couturier',
            'tisserand' => 'tisserand',
            'tissage' => 'tisserand',
            'sculpteur' => 'sculpteur',
            'sculpture' => 'sculpteur',
            'potier' => 'potier',
            'poterie' => 'potier',
            'forgeron' => 'forgeron',
            'forge' => 'forgeron',
            'bijoutier' => 'bijoutier',
            'bijou' => 'bijoutier',
            'joaillier' => 'bijoutier',
            'tanneur' => 'tanneur',
            'tannage' => 'tanneur',
            'corroyeur' => 'corroyeur',
            'musicien' => 'musicien',
            'musique' => 'musicien',

            // Métiers modernes
            'mécanicien' => 'mecanicien',
            'mécanique' => 'mecanicien',
            'coiffeur' => 'coiffeur',
            'coiffure' => 'coiffeur',
            'menuisier' => 'menuisier',
            'ébéniste' => 'menuisier',
            'plombier' => 'plombier',
            'plomberie' => 'plombier',
            'électricien' => 'électricien',
            'électricité' => 'électricien',
            'peintre' => 'peintre',
            'peinture' => 'peintre',
            'maçon' => 'maçon',
            'maçonnerie' => 'maçon',
            'charpentier' => 'charpentier',
            'serrurier' => 'serrurier',
            'vitrier' => 'vitrier',
            'carreleur' => 'carreleur',
            'vulcanisateur' => 'vulcanisateur',
            'pneu' => 'vulcanisateur',
        ];

        $message = strtolower($message);

        foreach ($crafts as $keyword => $craft) {
            if (str_contains($message, $keyword)) {
                return $craft;
            }
        }

        return null;
    }

    /**
     * Extraire la localisation mentionnée dans le message
     */
    private function extractLocation(string $message): ?string
    {
        $locations = [
            // Villes principales
            'cotonou', 'porto-novo', 'parakou', 'abomey', 'ouidah', 'djougou',
            'bohicon', 'kandi', 'natitingou', 'lokossa', 'abomey-calavi', 'allada',
            'ketou', 'pobè', 'sakété', 'savè', 'covè', 'zagnanado', 'za-kpota',

            // Quartiers de Cotonou
            'gbèdjromèdé', 'gbèdégbé', 'gbèdégbé-gare', 'gbèdégbé-plage', 'tokplégbé',
            'houeyiho', 'houéyiho', 'fidjrossè', 'fifi', 'kouhounou', 'kouhounou-gare',
            'kouhounou-plage', 'agla', 'agla-gare', 'agla-plage', 'vodje', 'vodjè',
            'sainte-rita', 'sainte rita', 'sainte-rita', 'cadjèhoun', 'cadjehoun',
            'cadjehoun-plage', 'cadjehoun-gare', 'jonquet', 'jonquet-plage', 'haie-vive',
            'haie vive', 'mènontin', 'menontin', 'ménontin', 'st-michel', 'saint-michel',
            'st michel', 'saint michel', 'missessinto', 'missèsinto', 'vossa', 'vôsa',
            'zongo', 'zongo-nima', 'totsi', 'totchè', 'totche', 'totché'
        ];

        $message = strtolower($message);

        foreach ($locations as $location) {
            if (str_contains($message, $location)) {
                return $location;
            }
        }

        return null;
    }

    /**
     * Extraire le nom du produit mentionné
     */
    private function extractProductName(string $message): ?string
    {
        $products = ['masque', 'sculpture', 'tissu', 'bijou', 'guèlèdè'];

        foreach ($products as $product) {
            if (str_contains($message, $product)) {
                return $product;
            }
        }

        return null;
    }

    /**
     * Extraire le nom du plat mentionné
     */
    private function extractDishName(string $message): ?string
    {
        $dishes = ['amiwo', 'akassa', 'aloko', 'atassi', 'tchoucoutou', 'wagashi'];

        foreach ($dishes as $dish) {
            if (str_contains($message, $dish)) {
                return $dish;
            }
        }

        return null;
    }

    /**
     * Récupérer l'historique des conversations
     */
    public function history(Request $request)
    {
        try {
            // Récupérer l'historique depuis la session ou la base de données
            $history = session('chatbot_history', []);

            return response()->json([
                'success' => true,
                'history' => $history,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot history error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique.',
            ], 500);
        }
    }

    /**
     * Effacer l'historique des conversations
     */
    public function clear(Request $request)
    {
        try {
            // Effacer l'historique de la session
            session()->forget('chatbot_history');

            return response()->json([
                'success' => true,
                'message' => 'Historique effacé avec succès.',
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot clear error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'effacement de l\'historique.',
            ], 500);
        }
    }

    /**
     * Générer des suggestions d'artisans quand aucun résultat n'est trouvé
     */
    private function getArtisanSuggestions(?string $craft, ?string $location): string
    {
        $suggestions = "Voici quelques suggestions :\n";

        // Suggestions de métiers populaires
        $popularCrafts = ['tisserand', 'sculpteur', 'couturier', 'menuisier', 'bijoutier'];
        if ($craft) {
            $suggestions .= "• Essayez un métier similaire : " . implode(', ', array_slice($popularCrafts, 0, 3)) . "\n";
        }

        // Suggestions de localisations
        if ($location) {
            $suggestions .= "• Élargissez votre recherche à d'autres quartiers de Cotonou\n";
        } else {
            $suggestions .= "• Précisez une localisation (ex: Cotonou, Porto-Novo, etc.)\n";
        }

        $suggestions .= "• Consultez notre [annuaire complet des artisans](" . route('artisans.index') . ")";

        return $suggestions;
    }

    /**
     * Gérer le contact avec un artisan spécifique
     */
    private function handleArtisanContact(int $artisanNumber): string
    {
        try {
            // Récupérer l'historique récent pour trouver les artisans suggérés
            $history = session('chatbot_history', []);
            $lastSearch = null;

            // Chercher la dernière recherche d'artisan dans l'historique
            foreach (array_reverse($history) as $entry) {
                if (str_contains($entry['bot'], 'artisan(s) pour vous')) {
                    $lastSearch = $entry['bot'];
                    break;
                }
            }

            if (!$lastSearch) {
                return "Je ne me souviens pas de la dernière recherche d'artisan. Pouvez-vous me redire ce que vous cherchez ?";
            }

            // Extraire les informations des artisans de la réponse précédente
            // Cette implémentation simplifiée - en production, on stockerait les IDs en session
            $artisans = \App\Models\Artisan::query()
                ->where('visible', true)
                ->with(['user'])
                ->take(5)
                ->get();

            if ($artisanNumber < 1 || $artisanNumber > $artisans->count()) {
                return "Le numéro d'artisan que vous avez indiqué n'est pas valide. Les numéros vont de 1 à {$artisans->count()}.";
            }

            $artisan = $artisans[$artisanNumber - 1];

            $response = "Parfait ! Voici les informations de contact pour **{$artisan->user->name}** :\n\n";
            $response .= "🔨 **Métier :** {$artisan->craft_label}\n";
            $response .= "📍 **Localisation :** {$artisan->location}\n";

            if ($artisan->years_experience) {
                $response .= "⏰ **Expérience :** {$artisan->years_experience} ans\n";
            }

            if ($artisan->whatsapp) {
                $response .= "📱 **WhatsApp :** {$artisan->whatsapp}\n";
            }

            if ($artisan->phone) {
                $response .= "📞 **Téléphone :** {$artisan->phone}\n";
            }

            if ($artisan->bio) {
                $response .= "\n📝 **À propos :** {$artisan->bio}\n";
            }

            $response .= "\n👉 [Voir le profil complet](" . route('artisans.show', $artisan) . ")\n";
            $response .= "\nN'hésitez pas à le contacter directement pour discuter de votre projet !";

            return $response;

        } catch (\Exception $e) {
            Log::error('Artisan contact error: ' . $e->getMessage());
            return "Désolé, je n'arrive pas à récupérer les informations de cet artisan. Veuillez réessayer.";
        }
    }
}
