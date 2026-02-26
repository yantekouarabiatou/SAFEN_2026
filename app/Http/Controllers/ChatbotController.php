<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\AIService;
use App\Models\ChatLog;

class ChatbotController extends Controller
{
    /**
     * Envoyer un message au chatbot
     */
    public function send(Request $request)
    {
        $request->validate([
            'message'  => 'required|string|max:1000',
            'language' => 'nullable|string|in:fr,en',
        ]);

        $userMessage = $request->message;
        $language    = $request->language ?? 'fr';

        try {
            // Récupérer l'historique de session (pour contexte IA)
            $history = session('chatbot_history', []);

            // Détecter l'intention
            $intent = $this->detectIntent($userMessage);

            // Générer la réponse
            $reply = match ($intent['type']) {
                'artisan_search'  => $this->handleArtisanSearch($userMessage, $intent),
                'artisan_contact' => $this->handleArtisanContact($intent['artisan_number']),
                'product_info'    => $this->handleProductInfo($userMessage, $intent),
                'dish_info'       => $this->handleDishInfo($userMessage, $intent),
                'cultural_info'   => $this->handleCulturalInfo($userMessage, $language, $history),
                default           => $this->handleGeneralQuestion($userMessage, $language, $history),
            };

            // ✅ Sauvegarder dans la session (pour contexte des prochains messages)
            $history[] = ['user' => $userMessage, 'bot' => $reply];
            session(['chatbot_history' => array_slice($history, -20)]);

            // ✅ Sauvegarder en base de données
            ChatLog::create([
                'session_id' => session()->getId(),
                'user_id'    => auth()->id(),
                'message'    => $userMessage,
                'response'   => $reply,
                'language'   => $language,
                'metadata'   => [
                    'intent'     => $intent['type'],
                    'ip'         => $request->ip(),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 200),
                ],
            ]);

            return response()->json([
                'success' => true,
                'reply'   => $reply,
                'intent'  => $intent['type'],
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'reply'   => $language === 'fr'
                    ? "Désolé, je rencontre une difficulté. Pouvez-vous reformuler votre question ?"
                    : "Sorry, I'm having trouble. Could you rephrase your question?",
            ], 500);
        }
    }

    /**
     * Récupérer l'historique depuis la session
     */
    public function history(Request $request)
    {
        try {
            $history = session('chatbot_history', []);
            return response()->json(['success' => true, 'history' => $history]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur historique.'], 500);
        }
    }

    /**
     * Effacer l'historique
     */
    public function clear(Request $request)
    {
        try {
            session()->forget('chatbot_history');
            return response()->json(['success' => true, 'message' => 'Historique effacé.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur effacement.'], 500);
        }
    }

    // =========================================================================
    //  DÉTECTION D'INTENTION
    // =========================================================================

    private function detectIntent(string $message): array
    {
        $message = strtolower($message);

        // Contact artisan par numéro
        if (preg_match('/(?:contacte|appelle|numéro|le)\s+(\d+)/i', $message, $m)) {
            return ['type' => 'artisan_contact', 'artisan_number' => (int) $m[1]];
        }
        if (preg_match('/artisan\s+(\d+)/i', $message, $m)) {
            return ['type' => 'artisan_contact', 'artisan_number' => (int) $m[1]];
        }

        // Recherche artisan
        $artisanKeywords = [
            'tailleur','mécanicien','coiffeur','menuisier','artisan','trouve','cherche','besoin',
            'réparer','confection','couture','tissage','sculpture','bijou','potier','forgeron',
            'tanneur','musicien','vulcanisateur','plombier','électricien','peintre','maçon',
            'charpentier','serrurier','vitrier','carreleur','professionnel','spécialiste',
        ];
        foreach ($artisanKeywords as $kw) {
            if (str_contains($message, $kw)) {
                return [
                    'type'     => 'artisan_search',
                    'craft'    => $this->extractCraft($message),
                    'location' => $this->extractLocation($message),
                ];
            }
        }

        // Produit
        foreach (['masque','sculpture','tissu','bijou','acheter','prix','produit'] as $kw) {
            if (str_contains($message, $kw)) {
                return ['type' => 'product_info', 'product' => $this->extractProductName($message)];
            }
        }

        // Plat
        foreach (['amiwo','akassa','tchoukoutou','tchapkalo','ablo','attassi','plat','cuisine','manger','recette','gastronomie'] as $kw) {
            if (str_contains($message, $kw)) {
                return ['type' => 'dish_info', 'dish' => $this->extractDishName($message)];
            }
        }

        // Culture
        foreach (['guèlèdè','vaudou','culture','tamtam','histoire','tradition','explique','c\'est quoi','zangbeto'] as $kw) {
            if (str_contains($message, $kw)) {
                return ['type' => 'cultural_info'];
            }
        }

        return ['type' => 'general'];
    }

    // =========================================================================
    //  HANDLERS
    // =========================================================================

    private function handleArtisanSearch(string $message, array $intent): string
    {
        $craft    = $intent['craft'] ?? null;
        $location = $intent['location'] ?? null;

        $query = \App\Models\Artisan::query()->where('visible', true);

        if ($craft)    $query->where('craft', 'like', "%{$craft}%");
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('neighborhood', 'like', "%{$location}%");
            });
        }

        $artisans = $query->take(3)->get();

        if ($artisans->isEmpty()) {
            return "Je n'ai pas trouvé d'artisan correspondant à votre recherche. Voulez-vous élargir la zone ou essayer un autre métier ?";
        }

        $response = "J'ai trouvé **" . $artisans->count() . " artisan(s)** pour vous :\n\n";
        foreach ($artisans as $i => $artisan) {
            $num = $i + 1;
            $response .= "**{$num}. {$artisan->user->name}**\n";
            $response .= "🔨 {$artisan->craft_label}\n";
            $response .= "📍 {$artisan->location}\n";
            if ($artisan->rating_avg > 0) {
                $response .= "⭐ {$artisan->rating_avg}/5\n";
            }
            $response .= "[Voir le profil](" . route('artisans.show', $artisan) . ")\n\n";
        }
        $response .= "Tapez **\"contacte le 1\"** pour obtenir les coordonnées du premier artisan.";
        return $response;
    }

    private function handleArtisanContact(int $artisanNumber): string
    {
        try {
            $artisans = \App\Models\Artisan::query()->where('visible', true)->with('user')->take(5)->get();

            if ($artisanNumber < 1 || $artisanNumber > $artisans->count()) {
                return "Numéro invalide. Les numéros vont de 1 à {$artisans->count()}.";
            }

            $artisan  = $artisans[$artisanNumber - 1];
            $response = "Voici les coordonnées de **{$artisan->user->name}** :\n\n";
            $response .= "🔨 {$artisan->craft_label}\n";
            $response .= "📍 {$artisan->location}\n";
            if ($artisan->phone)    $response .= "📞 {$artisan->phone}\n";
            if ($artisan->whatsapp) $response .= "📱 {$artisan->whatsapp}\n";
            $response .= "\n[Voir le profil complet](" . route('artisans.show', $artisan) . ")";
            return $response;
        } catch (\Exception $e) {
            return "Désolé, je n'arrive pas à récupérer ces informations. Veuillez réessayer.";
        }
    }

    private function handleProductInfo(string $message, array $intent): string
    {
        $productName = $intent['product'] ?? null;
        if ($productName) {
            $product = \App\Models\Product::where('name', 'like', "%{$productName}%")
                ->orWhere('name_local', 'like', "%{$productName}%")->first();

            if ($product) {
                $response  = "📦 **{$product->name}**";
                if ($product->name_local) $response .= " ({$product->name_local})";
                $response .= "\n\n";
                if ($product->description_cultural) {
                    $response .= substr($product->description_cultural, 0, 200) . "...\n\n";
                }
                $response .= "💰 {$product->formatted_price}\n";
                $response .= "👤 {$product->artisan->user->name}\n";
                $response .= "[Voir le produit](" . route('products.show', $product) . ")";
                return $response;
            }
        }
        return "Je peux vous aider à trouver des produits artisanaux ! Cherchez-vous des masques, sculptures, tissus ou bijoux ?";
    }

    private function handleDishInfo(string $message, array $intent): string
    {
        $dishName = $intent['dish'] ?? null;
        if ($dishName) {
            $dish = \App\Models\Dish::where('name', 'like', "%{$dishName}%")
                ->orWhere('name_local', 'like', "%{$dishName}%")->first();

            if ($dish) {
                $response  = "🍲 **{$dish->name}**";
                if ($dish->name_local) $response .= " ({$dish->name_local})";
                $response .= "\n\n";
                if ($dish->description) $response .= substr($dish->description, 0, 250) . "...\n\n";
                $response .= "🌍 {$dish->ethnic_origin} · {$dish->region}\n";
                if ($dish->ingredients && count($dish->ingredients) > 0) {
                    $response .= "🥘 " . implode(', ', array_slice($dish->ingredients, 0, 5)) . "\n";
                }
                $response .= "[Voir la recette](" . route('gastronomie.show', $dish) . ")";
                return $response;
            }
        }
        return "Je peux vous parler de la gastronomie béninoise ! Voulez-vous découvrir l'Amiwo, l'Akassa, le Tchoucoutou ou l'Atassi ?";
    }

    private function handleCulturalInfo(string $message, string $language, array $history): string
    {
        $aiService = new AIService();
        return $aiService->chatAnansi($message, $history, $language);
    }

    private function handleGeneralQuestion(string $message, string $language, array $history): string
    {
        $aiService = new AIService();
        return $aiService->chatAnansi($message, $history, $language);
    }

    // =========================================================================
    //  EXTRACTEURS
    // =========================================================================

    private function extractCraft(string $message): ?string
    {
        $crafts = [
            'tailleur' => 'couturier','couturier' => 'couturier','couture' => 'couturier',
            'tisserand' => 'tisserand','tissage' => 'tisserand',
            'sculpteur' => 'sculpteur','sculpture' => 'sculpteur',
            'potier' => 'potier','poterie' => 'potier',
            'forgeron' => 'forgeron','bijoutier' => 'bijoutier','bijou' => 'bijoutier',
            'mécanicien' => 'mecanicien','mécanique' => 'mecanicien',
            'coiffeur' => 'coiffeur','coiffure' => 'coiffeur',
            'menuisier' => 'menuisier','plombier' => 'plombier',
            'électricien' => 'électricien','peintre' => 'peintre',
            'maçon' => 'maçon','charpentier' => 'charpentier',
            'serrurier' => 'serrurier','vitrier' => 'vitrier',
            'carreleur' => 'carreleur','vulcanisateur' => 'vulcanisateur',
        ];
        $message = strtolower($message);
        foreach ($crafts as $keyword => $craft) {
            if (str_contains($message, $keyword)) return $craft;
        }
        return null;
    }

    private function extractLocation(string $message): ?string
    {
        $locations = [
            'cotonou','porto-novo','parakou','abomey','ouidah','djougou','bohicon',
            'kandi','natitingou','lokossa','abomey-calavi','allada',
            'houeyiho','fidjrossè','agla','cadjèhoun','cadjehoun','jonquet',
            'haie-vive','mènontin','saint-michel','zongo',
        ];
        $message = strtolower($message);
        foreach ($locations as $location) {
            if (str_contains($message, $location)) return $location;
        }
        return null;
    }

    private function extractProductName(string $message): ?string
    {
        foreach (['masque','sculpture','tissu','bijou','guèlèdè'] as $p) {
            if (str_contains($message, $p)) return $p;
        }
        return null;
    }

    private function extractDishName(string $message): ?string
    {
        foreach (['amiwo','akassa','aloko','atassi','tchoucoutou','wagashi'] as $d) {
            if (str_contains($message, $d)) return $d;
        }
        return null;
    }
}