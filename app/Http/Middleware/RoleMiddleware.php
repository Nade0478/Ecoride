<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise',
                'error_code' => 'AUTHENTICATION_REQUIRED'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->hasAnyRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Rôle insuffisant pour accéder à cette ressource',
                'required_roles' => $roles,
                'user_role' => $user->role,
                'error_code' => 'INSUFFICIENT_ROLE'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}