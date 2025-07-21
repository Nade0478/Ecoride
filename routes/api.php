<?php

use App\Http\Controllers\API\Avis;
use App\Http\Controllers\API\CarModel;
use App\Http\Controllers\API\Covoiturage;
use App\Http\Controllers\API\Marque;
use App\Http\Controllers\API\Notification;
use App\Http\Controllers\API\Role;
use App\Http\Controllers\API\Utilisateur;
use App\Http\Controllers\API\Voiture;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\API\TransactionsStripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/api', function () {
    return response()->json(['message' => 'Welcome to the API']);
});

Route::get('/api/utilisateur', function () {
    return response()->json(['message' => 'Utilisateur endpoint']);
});

Route::get('/api/role', function () {
    return response()->json(['message' => 'Role endpoint']);
});

Route::get('/api/voiture', function () {
    return response()->json(['message' => 'Voiture endpoint']);
});

Route::get('/api/marque', function () {
    return response()->json(['message' => 'Marque endpoint']);
});

Route::get('/api/car-model', function () {
    return response()->json(['message' => 'Car Model endpoint']);
});

Route::get('/api/avis', function () {
    return response()->json(['message' => 'Avis endpoint']);
});

Route::get('/api/covoiturage', function () {
    return response()->json(['message' => 'Covoiturage endpoint']);
});

Route::get('/api/notification', function () {
    return response()->json(['message' => 'Notification endpoint']);
});

Route::get('/api/role-utilisateur', function () {
    return response()->json(['message' => 'Role Utilisateur endpoint']);
});

// Route pour les utilisateurs
Route::apiResource('utilisateur', Utilisateur::class);
// Route pour les rôles
Route::apiResource('role', Role::class);
// Route pour les voitures
Route::apiResource('voiture', Voiture::class);
// Route pour les marques
Route::apiResource('marque', Marque::class);
// Route pour les modèles de voiture
Route::apiResource('car-model', CarModel::class);
// Route pour les avis
Route::apiResource('avis', Avis::class);
// Route pour les covoiturages
Route::apiResource('covoiturage', Covoiturage::class);
// Route pour les rôles des utilisateurs
Route::apiResource('role-utilisateur', Role::class);


// Route pour les configurations
Route::apiResource('configuration', ConfigurationController::class);


//Route pour stripe
Route::apiResource('transactions-stripe', TransactionsStripeController::class);
Route::post('payment-intent', [TransactionsStripeController::class, 'createPaymentIntent']);
