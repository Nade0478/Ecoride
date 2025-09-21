<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AvisController;
use App\Http\Controllers\API\CarModelController;
use App\Http\Controllers\API\CovoiturageController;
use App\Http\Controllers\API\MarqueController;
use App\Http\Controllers\API\MouvementController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ParticipationController;
use App\Http\Controllers\API\RegleController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ConfigurationController;

// Routes d'accueil de l'API
Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenue sur l\'API EcoRide',
        'version' => '1.0',
        'status' => 'active',
        'available_endpoints' => [
            'users' => '/api/users',
            'roles' => '/api/roles',
            'mouvements' => '/api/mouvements',
            'regles' => '/api/regles',
            'participations' => '/api/participations',
            'marques' => '/api/marques',
            'car-models' => '/api/car-models',
            'covoiturages' => '/api/covoiturages',
            'configurations' => '/api/configurations'
        ]
    ]);
});

// Routes API RESTful de base
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('mouvements', MouvementController::class);
Route::apiResource('regles', RegleController::class);
Route::apiResource('participations', ParticipationController::class);
Route::apiResource('marques', MarqueController::class);
Route::apiResource('car-models', CarModelController::class);
Route::apiResource('covoiturages', CovoiturageController::class);

// Routes spécifiques pour Regle
Route::get('regles/actives', [RegleController::class, 'active']);
Route::get('regles/type/{type}', [RegleController::class, 'byType']);

// Routes spécifiques pour Mouvement
Route::get('mouvements/user/{Iduser}', [MouvementController::class, 'getMouvementsByUser']);
Route::get('mouvements/credits', [MouvementController::class, 'credits']);
Route::get('mouvements/debits', [MouvementController::class, 'debits']);
Route::get('mouvements/solde/{Iduser}', [MouvementController::class, 'soldeUser']);
Route::get('mouvements/rapport/{annee}/{mois}', [MouvementController::class, 'rapportMensuel']);

// Routes spécifiques pour Participation
Route::prefix('participations')->group(function () {
    Route::get('user/{iduser}', [ParticipationController::class, 'getParticipationsByUser']);
    Route::get('covoiturage/{idcovoiturage}', [ParticipationController::class, 'getParticipationsByCovoiturage']);
    Route::get('{idCovoiturage}/{idUser}', [ParticipationController::class, 'show']);
    Route::put('{idCovoiturage}/{idUser}', [ParticipationController::class, 'update']);
    Route::delete('{idCovoiturage}/{idUser}', [ParticipationController::class, 'destroy']);
    Route::patch('{idCovoiturage}/{idUser}/confirmer', [ParticipationController::class, 'confirmer']);
});

// === ROUTES CONFIGURATION ECORIDE AVEC AUTHENTIFICATION ===
Route::middleware(['auth:sanctum'])->group(function () {

    // Routes CRUD Configuration (admin et employé)
    Route::apiResource('configurations', ConfigurationController::class);

    // Routes spécifiques Configuration
    Route::prefix('configurations')->group(function () {
        Route::get('category/{category}', [ConfigurationController::class, 'getByCategory']);
        Route::get('key/{key}', [ConfigurationController::class, 'getByKey']);
    });
});

// Routes temporaires pour les contrôleurs manquants
Route::get('voitures', function () {
    return response()->json([
        'message' => 'VoitureController not yet created',
        'note' => 'Run: php artisan make:controller API/VoitureController --api'
    ]);
});

Route::get('avis', function () {
    return response()->json([
        'message' => 'AvisController not yet created',
        'note' => 'Run: php artisan make:controller API/AvisController --api'
    ]);
});

Route::get('notifications', function () {
    return response()->json([
        'message' => 'NotificationController not yet created',
        'note' => 'Run: php artisan make:controller API/NotificationController --api'
    ]);
});

Route::prefix('auth')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'AuthController not yet created',
            'note' => 'Run: php artisan make:controller API/AuthController'
        ]);
    });
});

// Routes publiques
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Routes protégées
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});