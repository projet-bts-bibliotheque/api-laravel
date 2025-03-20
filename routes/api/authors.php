<?php

use App\Http\Controllers\AuthorsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion des auteurs
 * Utilisation du regroupement par contrôleur pour simplifier la définition des routes
 */
Route::controller(AuthorsController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::get("/authors", "index");  // Liste tous les auteurs
    Route::get("/authors/{id}", "show");  // Récupère un auteur spécifique par ID

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         * Permettent les opérations de modification (création, mise à jour, suppression)
         */
        Route::middleware("isStaff")->group(function() {
            Route::post("/authors", "store");  // Crée un nouvel auteur
            Route::put("/authors/{id}", "update");  // Met à jour un auteur existant
            Route::delete("/authors/{id}", "destroy");  // Supprime un auteur
        });

    });
});