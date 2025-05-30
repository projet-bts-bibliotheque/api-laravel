<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Force toutes les requêtes à utiliser le header Access-Control-Allow-Origin: *
 */
class CorsHeader {
    
    /**
     * Traite la requête entrante
     * 
     * @param Request $request La requête HTTP entrante
     * @param Closure $next Le callback de middleware suivant
     * @return Response La réponse HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Access-Control-Allow-Origin', '*');

        return $next($request);
    }

}