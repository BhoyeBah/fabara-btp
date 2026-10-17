<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protège l'administration. Un visiteur non connecté ou un client reçoit
 * une simple erreur 404 : il ne peut même pas savoir que l'admin existe.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}
