<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MouvementController;
use App\Http\Controllers\API\ConfigurationController;
use App\Http\Controllers\API\RegleController;
use App\Http\Controllers\API\ParticipationController;
use App\Http\Controllers\API\MarqueController;
use App\Http\Controllers\API\CarModelController;
use App\Http\Controllers\API\CovoiturageController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UtilisateurController;

// Routes d'accueil de l'API
Route::get('/', function () {
    return response()->json([
        'message' => 'Bienvenue sur l\'API EcoRide',
        'version' => '1.0',
        'status' => 'active',
        'available_endpoints' => [
            'utilisateurs' => '/api/utilisateurs',
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

// Routes API RESTful (seulement pour les contrôleurs existants)
Route::apiResource('utilisateurs', UtilisateurController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('mouvements', MouvementController::class);
Route::apiResource('configurations', ConfigurationController::class);
Route::apiResource('regles', RegleController::class);
Route::apiResource('participations', ParticipationController::class);
Route::apiResource('marques', MarqueController::class);
Route::apiResource('car-models', CarModelController::class);
Route::apiResource('covoiturages', CovoiturageController::class);

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

// Routes spécifiques pour Regle
Route::get('regles/actives', [RegleController::class, 'active']);
Route::get('regles/type/{type}', [RegleController::class, 'byType']);

// Routes spécifiques pour Mouvement
Route::get('mouvements/utilisateur/{userId}', [MouvementController::class, 'getMouvementsByUser']);
Route::get('mouvements/credits', [MouvementController::class, 'credits']);
Route::get('mouvements/debits', [MouvementController::class, 'debits']);
Route::get('mouvements/solde/{userId}', [MouvementController::class, 'soldeUtilisateur']);
Route::get('mouvements/rapport/{annee}/{mois}', [MouvementController::class, 'rapportMensuel']);

// Routes spécifiques pour Participation (avec clé composite)
Route::prefix('participations')->group(function () {
    Route::get('utilisateur/{userId}', [ParticipationController::class, 'getParticipationsByUser']);
    Route::get('covoiturage/{covoiturageId}', [ParticipationController::class, 'getParticipationsByCovoiturage']);
    Route::get('{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'show']);
    Route::put('{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'update']);
    Route::delete('{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'destroy']);
    Route::patch('{idCovoiturage}/{idUtilisateur}/confirmer', [ParticipationController::class, 'confirmer']);
});

// TODO: Routes à activer une fois les contrôleurs créés
/*
use App\Http\Controllers\API\VoitureController;
use App\Http\Controllers\API\AvisController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AuthController;

Route::apiResource('voitures', VoitureController::class);
Route::apiResource('avis', AvisController::class);
Route::apiResource('notifications', NotificationController::class);

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});
*/