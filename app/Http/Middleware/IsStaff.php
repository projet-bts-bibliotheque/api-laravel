<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Vérifie si l'utilisateur authentifié a les droits de personnel
 */
class IsStaff {
    
    /**
     * Filtre les requêtes pour autoriser uniquement le personnel
     * 
     * @param Request $request La requête HTTP entrante
     * @param Closure $next Le callback de middleware suivant
     * @return Response La réponse HTTP
     */
    public function handle(Request $request, Closure $next): Response {
        if (Auth::user()->role >= 1) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

}