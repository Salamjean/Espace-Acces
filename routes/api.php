<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Personel\PersonnelAuthentificationController;
use App\Http\Controllers\Api\Agent\AuthentificationAgentController;
use App\Http\Controllers\Api\Personel\CarteAccesController;
use App\Http\Controllers\Api\Agent\AgentPointageController;
use App\Http\Controllers\Api\Agent\ProfilAgentController;
use App\Http\Controllers\Api\Personel\ProfilPersonelController; // Ajouter cette ligne

// --- ROUTES Personnel ---
Route::post('/personnel/login', [PersonnelAuthentificationController::class, 'login']);

// Routes protégées par l'authentification Sanctum (pour le personnel)
Route::middleware('auth:sanctum')->group(function () {
    
    // Route de déconnexion
    Route::post('/personnel/logout', [PersonnelAuthentificationController::class, 'logout']);
    
    // --- ROUTES CARTE ACCES ---
    Route::get('/personnel/carte-acces', [CarteAccesController::class, 'showMyCard']);
    Route::get('/personnel/carte-acces/download', [CarteAccesController::class, 'downloadMyCard']);
    
    // --- NOUVELLES ROUTES PROFIL PERSONNEL ---
    Route::get('/personnel/profil', [ProfilPersonelController::class, 'getProfil']);
    Route::put('/personnel/profil', [ProfilPersonelController::class, 'updateProfil']);
    Route::post('/personnel/profil/photo', [ProfilPersonelController::class, 'updatePhotoProfil']);
    Route::delete('/personnel/profil/photo', [ProfilPersonelController::class, 'deletePhotoProfil']);
    Route::post('/personnel/profil/piece-identite', [ProfilPersonelController::class, 'updatePieceIdentite']);
    Route::put('/personnel/profil/password', [ProfilPersonelController::class, 'changePassword']);
    
});

// --- ROUTES AGENT ---
Route::post('/agent/login', [AuthentificationAgentController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

     // Route de déconnexion
    Route::post('/agent/logout', [AuthentificationAgentController::class, 'logout']);
    
    // --- ROUTES POINTAGE AGENT ---
    Route::post('/agent/pointer-arrivee', [AgentPointageController::class, 'pointerArrivee']);
    Route::post('/agent/pointer-depart', [AgentPointageController::class, 'pointerDepart']);
    Route::get('/agent/statut-pointage', [AgentPointageController::class, 'getStatutPointage']);
    
    // --- ROUTES PROFIL AGENT ---
    Route::get('/agent/profil', [ProfilAgentController::class, 'getProfil']);
    Route::put('/agent/profil', [ProfilAgentController::class, 'updateProfil']);
    Route::post('/agent/profil/photo', [ProfilAgentController::class, 'updatePhotoProfil']);
    Route::delete('/agent/profil/photo', [ProfilAgentController::class, 'deletePhotoProfil']);
    Route::put('/agent/profil/password', [ProfilAgentController::class, 'changePassword']);
   
});