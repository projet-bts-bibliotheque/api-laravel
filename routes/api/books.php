<?php

use App\Http\Controllers\BooksController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion des livres
 * Utilisation du regroupement par contrôleur pour simplifier la définition des routes
 */
Route::controller(BooksController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::get("/books", "index");  // Liste tous les livres
    Route::get("/books/{id}", "show");  // Récupère un livre spécifique par ISBN

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         * Permettent les opérations de modification (création, mise à jour, suppression)
         */
        Route::middleware("isStaff")->group(function() {
            Route::post("/books", "store");  // Crée un nouveau livre
            Route::put("/books/{id}", "update");  // Met à jour un livre existant
            Route::delete("/books/{id}", "destroy");  // Supprime un livre
        });

    });
});