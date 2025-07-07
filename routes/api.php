<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Utilisateur;
use App\Http\Controllers\API\Role;
use App\Http\Controllers\API\Voiture;
use App\Http\Controllers\API\Marque;
use App\Http\Controllers\API\CarModel;
use App\Http\Controllers\API\Avis;
use App\Http\Controllers\API\Covoiturage;
use App\Http\Controllers\API\Notification;

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

Route::get('/api/role-voiture', function () {
    return response()->json(['message' => 'Role Voiture endpoint']);
});
Route::get('/api/role-marque', function () {
    return response()->json(['message' => 'Role Marque endpoint']);
});
Route::get('/api/role-car-model', function () {
    return response()->json(['message' => 'Role Car Model endpoint']);
});
Route::get('/api/role-avis', function () {
    return response()->json(['message' => 'Role Avis endpoint']);
});
Route::get('/api/role-covoiturage', function () {
    return response()->json(['message' => 'Role Covoiturage endpoint']);
});
Route::get('/api/role-notification', function () {
    return response()->json(['message' => 'Role Notification endpoint']);
});
Route::get('/api/role-role-utilisateur', function () {
    return response()->json(['message' => 'Role Role Utilisateur endpoint']);
});
