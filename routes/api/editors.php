<?php

use App\Http\Controllers\EditorsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion des éditeurs
 * Utilisation du regroupement par contrôleur pour simplifier la définition des routes
 */
Route::controller(EditorsController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::get("/editors", "index");  // Liste tous les éditeurs
    Route::get("/editors/{id}", "show");  // Récupère un éditeur spécifique par ID

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         * Permettent les opérations de modification (création, mise à jour, suppression)
         */
        Route::middleware("isStaff")->group(function() {
            Route::post("/editors", "store");  // Crée un nouvel éditeur
            Route::put("/editors/{id}", "update");  // Met à jour un éditeur existant
            Route::delete("/editors/{id}", "destroy");  // Supprime un éditeur
        });

    });
});