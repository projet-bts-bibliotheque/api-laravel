<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin {

    public function handle(Request $request, Closure $next): Response {
        if (Auth::user()->role >= 2) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

}
