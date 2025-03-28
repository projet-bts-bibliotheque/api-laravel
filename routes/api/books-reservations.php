<?php

use App\Http\Controllers\BooksReservationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion des salles
 * Utilisation du regroupement par contrôleur pour simplifier la définition des routes
 */
Route::controller(BooksReservationController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::get('/reservation/books', 'index');

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {
        Route::get('/reservation/books/me', 'showReservations');

        Route::post('/reservation/books', 'store');
        Route::post('/reservation/books/return', 'returnBook');

        Route::delete('/reservation/books/{reservationId}', 'destroy');

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         * Permettent les opérations de modification (création, mise à jour, suppression)
         */
        Route::middleware("isStaff")->group(function() {
            Route::get('/reservation/books/{userId}', 'showUserReservations');
        });

    });
});