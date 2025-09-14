<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\MouvementController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\API\RegleController;
use App\Http\Controllers\API\ParticipationController;
use App\Http\Controllers\API\VoitureController;
use App\Http\Controllers\API\MarqueController;
use App\Http\Controllers\API\CarModelController;
use App\Http\Controllers\API\AvisController;
use App\Http\Controllers\API\CovoiturageController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UtilisateurController;

// ✅ Route d'accueil
Route::get('/', function () {
    return response()->json(['message' => 'Bienvenue sur l’API 🚀']);
});

// ✅ Routes API RESTful
Route::apiResource('utilisateur', UtilisateurController::class);
Route::apiResource('role', RoleController::class);
Route::apiResource('mouvement', MouvementController::class);
Route::apiResource('configuration', ConfigurationController::class);
Route::apiResource('regle', RegleController::class);
Route::apiResource('participation', ParticipationController::class);
Route::apiResource('voiture', VoitureController::class);
Route::apiResource('marque', MarqueController::class);
Route::apiResource('car-model', CarModelController::class);
Route::apiResource('avis', AvisController::class);
Route::apiResource('covoiturage', CovoiturageController::class);
Route::apiResource('notification', NotificationController::class);

// ✅ Routes d'authentification
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// À ajouter dans votre fichier routes/api.php

// Routes spécifiques pour Regle
Route::get('regles/actives', [RegleController::class, 'active']);
Route::get('regles/type/{type}', [RegleController::class, 'byType']);

// Routes spécifiques pour Participation
Route::get('participations/utilisateur/{userId}', [ParticipationController::class, 'getParticipationsByUser']);
Route::get('participations/covoiturage/{covoiturageId}', [ParticipationController::class, 'getParticipationsByCovoiturage']);
Route::patch('participations/{idCovoiturage}/{idUtilisateur}/confirmer', [ParticipationController::class, 'confirmer']);

// Routes spécifiques pour Mouvement
Route::get('mouvements/utilisateur/{userId}', [MouvementController::class, 'getMouvementsByUser']);
Route::get('mouvements/credits', [MouvementController::class, 'credits']);
Route::get('mouvements/debits', [MouvementController::class, 'debits']);
Route::get('mouvements/solde/{userId}', [MouvementController::class, 'soldeUtilisateur']);
Route::get('mouvements/rapport/{annee}/{mois}', [MouvementController::class, 'rapportMensuel']);

// Routes pour les participations avec clé composite
Route::get('participations/{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'show']);
Route::put('participations/{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'update']);
Route::delete('participations/{idCovoiturage}/{idUtilisateur}', [ParticipationController::class, 'destroy']);
