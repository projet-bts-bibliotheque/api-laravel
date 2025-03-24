<?php

use App\Http\Controllers\RoomsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion des salles
 * Utilisation du regroupement par contrôleur pour simplifier la définition des routes
 */
Route::controller(RoomsController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::get("/rooms", "index");  // Liste tous les salles
    Route::get("/rooms/{id}", "show");  // Récupère une salle spécifique par ID

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         * Permettent les opérations de modification (création, mise à jour, suppression)
         */
        Route::middleware("isStaff")->group(function() {
            Route::post("/rooms", "store");  // Crée une nouvelle salle
            Route::put("/rooms/{id}", "update");  // Met à jour une salle existante
            Route::delete("/rooms/{id}", "destroy");  // Supprime une salle
        });

    });
});