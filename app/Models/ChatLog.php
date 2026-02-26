<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ChatLog extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'response',
        'language',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // =========================================================================
    //  RELATIONS
    // =========================================================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================================
    //  SCOPES UTILES POUR ANALYSER LES CONVERSATIONS
    // =========================================================================

    /** Questions des 7 derniers jours */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    /** Filtrer par langue */
    public function scopeInLanguage(Builder $query, string $lang): Builder
    {
        return $query->where('language', $lang);
    }

    /** Filtrer par intention */
    public function scopeByIntent(Builder $query, string $intent): Builder
    {
        return $query->whereJsonContains('metadata->intent', $intent);
    }

    // =========================================================================
    //  MÉTHODES D'ANALYSE
    // =========================================================================

    /**
     * Questions les plus fréquentes (pour améliorer le chatbot)
     * Usage : ChatLog::topQuestions(10)
     */
    public static function topQuestions(int $limit = 10): \Illuminate\Support\Collection
    {
        return static::select('message', \DB::raw('count(*) as total'))
            ->groupBy('message')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Statistiques par intention
     * Usage : ChatLog::intentStats()
     */
    public static function intentStats(): \Illuminate\Support\Collection
    {
        return static::select(
                \DB::raw("JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.intent')) as intent"),
                \DB::raw('count(*) as total')
            )
            ->groupBy('intent')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Nombre de conversations aujourd'hui
     */
    public static function todayCount(): int
    {
        return static::whereDate('created_at', today())->count();
    }
}