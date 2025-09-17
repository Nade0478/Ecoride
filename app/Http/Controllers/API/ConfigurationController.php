<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigurationRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use App\Models\Configuration;
use App\Http\Resources\ConfigurationResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Configuration::query();
        $user = $request->user();

        // Filtrage selon le rôle
        if ($user && $user->hasRole('employee')) {
            $query->whereIn('categorie', ['general', 'moderation', 'notifications'])
                  ->where('modifiable_interface', true);
        }

        // Filtres optionnels
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('modifiable_interface')) {
            $query->where('modifiable_interface', $request->boolean('modifiable_interface'));
        }

        $configurations = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ConfigurationResource::collection($configurations),
            'pagination' => [
                'current_page' => $configurations->currentPage(),
                'last_page' => $configurations->lastPage(),
                'per_page' => $configurations->perPage(),
                'total' => $configurations->total(),
            ]
        ]);
    }

    public function store(StoreConfigurationRequest $request): JsonResponse
    {
        $configuration = Configuration::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Configuration créée avec succès',
            'data' => new ConfigurationResource($configuration)
        ], 201);
    }

    public function show(Request $request, Configuration $configuration): JsonResponse
    {
        if ($this->canAccessConfiguration($request, $configuration)) {
            return response()->json([
                'success' => true,
                'data' => new ConfigurationResource($configuration)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Accès non autorisé à cette configuration',
            'error_code' => 'CONFIGURATION_ACCESS_DENIED'
        ], 403);
    }

    public function update(UpdateConfigurationRequest $request, Configuration $configuration): JsonResponse
    {
        $configuration->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Configuration mise à jour avec succès',
            'data' => new ConfigurationResource($configuration)
        ]);
    }

    public function destroy(Request $request, Configuration $configuration): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent supprimer des configurations',
                'error_code' => 'ADMIN_REQUIRED'
            ], 403);
        }

        $configuration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Configuration supprimée avec succès'
        ], 204);
    }

    public function getByCategory(Request $request, string $categorie): JsonResponse
    {
        $user = $request->user();

        $configurations = Configuration::where('categorie', $categorie)
            ->when(!$user || !$user->hasRole('admin'), function ($query) {
                return $query->where('modifiable_interface', true);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => ConfigurationResource::collection($configurations),
            'categorie' => $categorie,
            'count' => $configurations->count()
        ]);
    }

    public function getByKey(Request $request, string $cle): JsonResponse
    {
        $configuration = Configuration::where('cle', $cle)->first();

        if (!$configuration) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration non trouvée',
                'error_code' => 'CONFIGURATION_NOT_FOUND'
            ], 404);
        }

        if (!$this->canAccessConfiguration($request, $configuration)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé à cette configuration',
                'error_code' => 'CONFIGURATION_ACCESS_DENIED'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new ConfigurationResource($configuration)
        ]);
    }

    private function canAccessConfiguration(Request $request, Configuration $configuration): bool
    {
        $user = $request->user();

        if (!$user) return false;

        if ($user->hasRole('admin')) return true;

        if ($user->hasRole('employee')) {
            return $configuration->modifiable_interface &&
                   in_array($configuration->categorie, ['general', 'moderation', 'notifications']);
        }

        return false;
    }
}