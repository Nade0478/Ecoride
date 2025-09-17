<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Configuration;

class ConfigurationAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $configId = $request->route('configuration');

        if ($configId) {
            $config = Configuration::find($configId);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration introuvable',
                    'error_code' => 'CONFIGURATION_NOT_FOUND'
                ], 404);
            }

            // Vérifier l'accès selon le niveau requis
            if (!$this->canAccess($user, $config)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé à cette configuration',
                    'required_level' => $config->niveau_acces,
                    'user_role' => $user->role,
                    'error_code' => 'INSUFFICIENT_ACCESS_LEVEL'
                ], 403);
            }
        }

        return $next($request);
    }

    private function canAccess($user, Configuration $config): bool
    {
        return match($config->niveau_acces) {
            'public' => true,
            'employee' => $user && $user->hasAnyRole(['admin', 'employee']),
            'admin' => $user && $user->isAdmin(),
            default => false
        };
    }
}