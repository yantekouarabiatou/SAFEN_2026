<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  Les rôles autorisés (ex: 'admin', 'super-admin')
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function handle(Request $request, Closure $next, ...$roles): Response
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Accepte 'admin|super-admin' ou 'admin,super-admin'
    $requiredRoles = [];
    foreach ($roles as $roleString) {
        $parts = preg_split('/[|,]/', $roleString, -1, PREG_SPLIT_NO_EMPTY);
        $requiredRoles = array_merge($requiredRoles, array_map('trim', $parts));
    }
    $requiredRoles = array_unique($requiredRoles);

    if ($user->hasAnyRole($requiredRoles)) {
        return $next($request);
    }

    abort(403, 'Accès refusé : vous n\'avez pas les rôles nécessaires.');
}
}
