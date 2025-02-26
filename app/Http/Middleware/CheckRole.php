<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importez la classe Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login'); // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'Accès non autorisé.'); // Affiche une erreur 403 si l'utilisateur n'a pas le rôle requis
        }

        return $next($request); // Permet à la requête de continuer si l'utilisateur a le rôle requis
    }
}
