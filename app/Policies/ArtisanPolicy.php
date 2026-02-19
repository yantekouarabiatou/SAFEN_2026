<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Artisan;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArtisanPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si l'utilisateur peut gérer les artisans (admin)
     */
    public function manageArtisans(User $user)
    {
        return $user->role === 'admin' || $user->role === 'super_admin';
    }

    /**
     * Vérifie si l'utilisateur peut créer un artisan
     */
    public function create(User $user)
    {
        // Tout utilisateur authentifié peut créer un artisan
        return $user !== null;
    }

    /**
     * Vérifie si l'utilisateur peut voir un artisan (même s'il est en attente)
     */
    public function view(User $user, Artisan $artisan)
    {
        // L'admin peut tout voir
        if ($user->isAdmin()) {
            return true;
        }
        
        // L'artisan peut voir son propre profil
        if ($user->id === $artisan->user_id) {
            return true;
        }
        
        // Les autres utilisateurs ne peuvent voir que les artisans approuvés
        return $artisan->status === 'approved';
    }

    /**
     * Vérifie si l'utilisateur peut mettre à jour un artisan
     */
    public function update(User $user, Artisan $artisan)
    {
        return $user->id === $artisan->user_id || $user->isAdmin();
    }

    /**
     * Vérifie si l'utilisateur peut supprimer un artisan
     */
    public function delete(User $user, Artisan $artisan)
    {
        return $user->id === $artisan->user_id || $user->isAdmin();
    }

    /**
     * Vérifie si l'utilisateur peut approuver/rejeter un artisan
     */
    public function approve(User $user, Artisan $artisan)
    {
        return $user->isAdmin();
    }
}