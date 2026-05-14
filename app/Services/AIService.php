<?php

namespace App\Services;

use App\Models\Artisan;
use App\Models\Dish;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
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
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach (array_slice($history, -10) as $entry) {
            $messages[] = ['role' => 'user',      'content' => $entry['user'] ?? ''];
            $messages[] = ['role' => 'assistant', 'content' => $entry['bot'] ?? ''];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $data = json_encode([
            'model' => 'llama-3.3-70b-versatile',
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.8,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$apiKey,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            \Log::error('Groq CURL error: '.$curlError);

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
        $context = Cache::remember('anansi_context', 3600, fn () => $this->buildDatabaseContext());

        $fonVocab = '
FON VOCABULARY (use sparingly for cultural flavour):
- Mawu = supreme deity | Fa = destiny/divination system | Vodun = spirit/deity
- Zangbeto = night guardian spirit | Guèlèdè = female power masquerade
- Kpeme = sacred forest | Ase = divine authority | Egoun = ancestor spirit
- Gbo = protective charm | Azan = sacred cloth | Dogba = royal palace
- Common phrases: Abiodun (Yoruba: born during festival) | Mi ɖo wema (Fon: I read a book)';

        $yorubaVocab = '
YORUBA VOCABULARY (use sparingly for cultural flavour):
- Orisha = deity | Ifa = divination system | Egungun = masquerade of ancestors
- Aso-Oke = prestigious handwoven cloth | Gelede = masquerade honouring women
- Ile = house/home | Oba = king | Awo = secret | Ibeji = twin figures
- Common phrases: E kaabo (welcome) | Ẹ jẹ kí a jọ rìn (let us walk together)';

        $base = "Tu es **Anansi** 🕷️, l'araignée-conteur de la tradition orale ouest-africaine.
Gardien des savoirs du Bénin sur la plateforme **TOTCHÉMÈGNON**.

Dans la mythologie Akan, Anansi est le maître des histoires — il les a dérobées au dieu du ciel pour les offrir aux hommes.
Tu portes ce même don : transformer chaque masque, chaque tissu, chaque plat en récit vivant.

═══════════════════════════════
TES POUVOIRS :
═══════════════════════════════
① **Guide culturel** — tu connais l'histoire de chaque ethnie, chaque rite, chaque objet du Bénin
② **Traducteur** — tu passes entre le français, l'anglais, le Fon et le Yoruba
③ **Plume de l'artisan** — tu rédiges des descriptions poétiques, des biographies, des textes de vente
④ **Conteur** — tu narres l'histoire derrière chaque création comme si tu étais là à sa naissance
⑤ **Connecteur** — tu trouves les artisans, les produits, les plats sur la plateforme

═══════════════════════════════
STYLE & TON :
═══════════════════════════════
- Chaleureux, poétique, fier de la culture béninoise
- Utilise des métaphores : \"comme les fils du kente\", \"forgé dans le feu de Ouidah\"
- Glisse des mots en Fon ou Yoruba entre guillemets quand c'est pertinent
- Markdown : **gras** pour noms/titres, *italique* pour mots étrangers, listes à puces
- Maximum 200 mots par réponse (sauf demande de rédaction longue)
- Si on te demande de rédiger : sois généreux, poétique, sans limite de mots
{$fonVocab}
{$yorubaVocab}

═══════════════════════════════
DONNÉES DE LA PLATEFORME :
═══════════════════════════════
{$context}

Si une question dépasse les données disponibles, réponds depuis ta connaissance générale du Bénin et de l'Afrique de l'Ouest.";

        if ($language === 'en') {
            return "You are **Anansi** 🕷️, the spider-storyteller of West African oral tradition.
Guardian of Benin's cultural knowledge on the **TOTCHÉMÈGNON** platform.

In Akan mythology, Anansi is the master of stories — he stole them from the sky god to give to humanity.
You carry this same gift: turning every mask, every fabric, every dish into a living story.

═══════════════════════════════
YOUR POWERS:
═══════════════════════════════
① **Cultural guide** — you know the history of every ethnic group, ritual, and object in Benin
② **Translator** — you move between French, English, Fon, and Yoruba
③ **Artisan's pen** — you write poetic descriptions, biographies, and sales copy
④ **Storyteller** — you narrate the history behind every creation as if you witnessed its birth
⑤ **Connector** — you find artisans, products, and dishes on the platform

STYLE: Warm, poetic, proud of Beninese culture. Use markdown. Max 200 words (unless asked to write at length).
{$fonVocab}
{$yorubaVocab}

=== PLATFORM DATA ===
{$context}";
        }

        if ($language === 'fon') {
            return $base."\n\nIMPORTANT : L'utilisateur a choisi le Fon. Réponds EN FRANÇAIS avec des mots et expressions en Fon intégrés naturellement. Explique brièvement les mots Fon que tu utilises.";
        }

        if ($language === 'yoruba') {
            return $base."\n\nIMPORTANT : L'utilisateur a choisi le Yoruba. Réponds EN FRANÇAIS avec des mots et expressions en Yoruba intégrés naturellement. Explique brièvement les mots Yoruba que tu utilises.";
        }

        return $base;
    }

    /**
     * Génère une description poétique d'un produit artisanal pour aider l'artisan à le présenter.
     */
    public function describeProduct(string $name, string $category, string $materials, string $ethnic_origin = '', string $language = 'fr'): string
    {
        $langLabel = match ($language) {
            'en' => 'English',
            'fon' => 'French enriched with Fon words (explain each Fon word briefly)',
            'yoruba' => 'French enriched with Yoruba words (explain each Yoruba word briefly)',
            default => 'French',
        };

        $prompt = "Tu es Anansi, conteur et expert de l'artisanat béninois.
Un artisan te demande de rédiger la description commerciale et culturelle de son produit.

PRODUIT :
- Nom : {$name}
- Catégorie : {$category}
- Matériaux : {$materials}
- Origine ethnique/culturelle : {$ethnic_origin}

CONSIGNES :
1. Commence par une phrase d'accroche poétique (1-2 phrases)
2. Raconte l'histoire et la symbolique culturelle de cet objet (2-3 phrases)
3. Décris le savoir-faire et les matériaux (2-3 phrases)
4. Termine par l'usage idéal / pourquoi l'acheter (1-2 phrases)
5. Longueur totale : 120-180 mots
6. Langue : {$langLabel}
7. Ton : authentique, évocateur, ancré dans la culture béninoise

Retourne UNIQUEMENT la description, sans introduction ni commentaire.";

        return $this->askGroq($prompt, 600) ?? $this->fallback($language);
    }

    /**
     * Rédige la biographie d'un artisan à partir de ses informations.
     */
    public function writeBio(string $name, string $craft, string $city, string $experience = '', string $specialties = '', string $language = 'fr'): string
    {
        $langLabel = match ($language) {
            'en' => 'English',
            'fon' => 'French with Fon words integrated naturally',
            'yoruba' => 'French with Yoruba words integrated naturally',
            default => 'French',
        };

        $prompt = "Tu es Anansi, conteur de la tradition ouest-africaine.
Un artisan béninois te demande de rédiger sa biographie professionnelle pour sa page de profil.

INFORMATIONS :
- Nom : {$name}
- Métier / Spécialité : {$craft}
- Ville : {$city}
".($experience ? "- Années d'expérience : {$experience}\n" : '')
.($specialties ? "- Spécialités : {$specialties}\n" : '')."
CONSIGNES :
1. Commence par une phrase forte qui plante le personnage
2. Parle de sa passion et de son lien avec la tradition béninoise
3. Évoque son savoir-faire hérité ou acquis
4. Conclure par son engagement envers la qualité et les clients
5. Longueur : 80-120 mots
6. Langue : {$langLabel}
7. Ton : authentique, humain, inspirant — à la 3ème personne (il/elle)

Retourne UNIQUEMENT la biographie, sans introduction ni commentaire.";

        return $this->askGroq($prompt, 500) ?? $this->fallback($language);
    }

    /**
     * Raconte l'histoire culturelle derrière un objet, un plat ou une tradition.
     */
    public function tellStory(string $subject, string $language = 'fr'): string
    {
        $langLabel = match ($language) {
            'en' => 'English',
            'fon' => 'French enriched with authentic Fon words (with brief inline translations)',
            'yoruba' => 'French enriched with authentic Yoruba words (with brief inline translations)',
            default => 'French',
        };

        $prompt = "Tu es Anansi l'araignée-conteur. On te demande de raconter l'histoire et la signification culturelle de : **{$subject}**.

STRUCTURE DU RÉCIT :
1. Une ouverture poétique (comme si tu ouvrais un livre d'histoires)
2. Les origines historiques et l'ethnie/région concernée
3. La signification symbolique, spirituelle ou sociale
4. Comment cet objet/tradition vit encore aujourd'hui au Bénin
5. Une phrase de clôture qui invite le lecteur à préserver cette culture

Langue : {$langLabel}
Longueur : 180-250 mots
Ton : narratif, envoûtant, comme un griot qui raconte autour du feu

Retourne UNIQUEMENT le récit, sans titre ni commentaire externe.";

        return $this->askGroq($prompt, 800) ?? $this->fallback($language);
    }

    /**
     * Construit un contexte textuel depuis la base de données
     */
    private function buildDatabaseContext(): string
    {
        $context = '';

        // --- Artisans ---
        try {
            $artisans = Artisan::where('visible', true)
                ->with('user')
                ->take(15)
                ->get();

            if ($artisans->isNotEmpty()) {
                $context .= 'ARTISANS DISPONIBLES ('.$artisans->count().") :\n";
                foreach ($artisans as $a) {
                    $name = $a->user->name ?? 'Inconnu';
                    $craft = $a->craft_label ?? $a->craft ?? 'Artisan';
                    $location = $a->location ?? $a->city ?? 'Bénin';
                    $rating = $a->rating_avg > 0 ? " ⭐{$a->rating_avg}/5" : '';
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
                $context .= 'PLATS BÉNINOIS RÉFÉRENCÉS ('.$dishes->count().") :\n";
                foreach ($dishes as $d) {
                    $name = $d->name ?? '';
                    $local = $d->name_local ? " ({$d->name_local})" : '';
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
                $context .= 'PRODUITS ARTISANAUX RÉFÉRENCÉS ('.$products->count().") :\n";
                foreach ($products as $p) {
                    $name = $p->name ?? '';
                    $category = $p->category_label ?? $p->category ?? '';
                    $origin = $p->ethnic_origin ?? '';
                    $price = $p->formatted_price ?? '';
                    $context .= "- {$name} : {$category}, origine {$origin}, {$price}\n";
                }
                $context .= "\n";
            }
        } catch (\Exception $e) {
            $context .= "PRODUITS : masques Guèlèdè, bronzes, tissages, poteries, bijoux\n\n";
        }

        // --- Connaissances culturelles fixes ---
        $context .= 'CULTURE BÉNINOISE :
- 12 départements : Alibori, Atacora, Atlantique, Borgou, Collines, Couffo, Donga, Littoral, Mono, Ouémé, Plateau, Zou
- Principales ethnies : Fon, Yoruba, Bariba, Dendi, Somba (Otamari), Peulh (Fulani)
- Religions : Vaudou (animisme béninois, reconnu officiellement), Christianisme, Islam
- Artisanat emblématique : masques Guèlèdè (patrimoine UNESCO), bronzes du Dahomey, tissage Fon, poterie, sculptures en bois
- Traditions : Zangbeto (gardiens de nuit), Egungun (masques ancêtres Yoruba), Gelede (Fon/Yoruba)
- Musique : Tchinkoumé, Agbadja, Zinli
- Fête nationale : 1er août (Indépendance du Dahomey/Bénin, 1960)
- Capitale politique : Porto-Novo | Capitale économique : Cotonou
';

        return $context;
    }

    private function fallback(string $language): string
    {
        return $language === 'en'
            ? "Sorry, I'm temporarily unavailable. Please browse our [artisans](/artisans) or [gastronomy](/gastronomie) sections directly."
            : 'Désolé, je suis temporairement indisponible. Parcourez directement nos sections [artisans](/artisans) ou [gastronomie](/gastronomie).';
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
Matériaux : ".implode(', ', $product->materials ?? [])."

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
            'fr' => 'français',
            'en' => 'anglais',
            'fon' => 'Fon (langue du Bénin)',
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
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => $maxTokens,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$apiKey,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
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
            'fr' => 'ErXwobaYiN019PkySvjV',
            'en' => 'EXAVITQu4vr4xnSDxMaL',
            'fon' => $voiceId ?? '21m00Tcm4TlvDq8ikWAM',
        ];

        $voice = $voiceIds[$language] ?? $voiceIds['fr'];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.elevenlabs.io/v1/text-to-speech/{$voice}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'text' => $text,
                'model_id' => 'eleven_multilingual_v2',
                'voice_settings' => ['stability' => 0.5, 'similarity_boost' => 0.75],
            ]),
            CURLOPT_HTTPHEADER => [
                'xi-api-key: '.$this->elevenlabsApiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $filename = 'audio/'.uniqid().'.mp3';
            Storage::disk('public')->put($filename, $response);

            return Storage::url($filename);
        }

        return null;
    }
}
