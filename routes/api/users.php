<?php

use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes pour la gestion de l'authentification et des utilisateurs
 * Inclut les fonctionnalités d'inscription, connexion, réinitialisation de mot de passe et gestion de compte
 */
Route::controller(AuthController::class)->group(function() {
    /**
     * Routes publiques - accessibles sans authentification
     */
    Route::post("/register", "register")->name("register");  // Inscription d'un nouvel utilisateur
    Route::post("/login", "login")->name("login");  // Connexion utilisateur
    
    /**
     * Route pour afficher la page de réinitialisation de mot de passe
     * Renvoie simplement le token au format JSON dans cette API
     */
    Route::get("/password-reset/{token}", function(string $token) {
        return response()->json([
            "token" => $token
        ]);
    })->name('password.reset');

    Route::post("/forgot-password", "forgotPassword");  // Demande de réinitialisation de mot de passe
    Route::post("/reset-password", "resetPassword");  // Confirmation de réinitialisation avec nouveau mot de passe

    /**
     * Routes protégées - nécessitent une authentification via Sanctum
     */
    Route::middleware("auth:sanctum")->group(function() {
        Route::get("/me", "me");  // Récupère les informations de l'utilisateur connecté
        Route::delete("/me", "delete");  // Supprime le compte de l'utilisateur connecté
        Route::put("/me", "update");  // Met à jour les informations de l'utilisateur connecté

        /**
         * Routes pour la vérification d'email
         * La route d'envoi est limitée à 6 requêtes par minute (protection contre les abus)
         */
        Route::post('/email/verification-notification', "sendVerifyEmail")->middleware(['throttle:6,1'])->name('verification.send');
        Route::get('/email/verify/{id}/{hash}', "verifyEmail")->name('verification.verify');

        /**
         * Routes réservées au personnel - nécessitent un rôle staff (role >= 1)
         */
        Route::middleware("isStaff")->group(function() {
            Route::get("/users", "index");  // Liste tous les utilisateurs
            Route::post("/forgot-password/{id}", "forgotPasswordOther");  // Réinitialise le mot de passe d'un autre utilisateur
        });

        /**
         * Routes réservées aux administrateurs - nécessitent un rôle admin (role >= 2)
         * Permettent des opérations sensibles sur les comptes utilisateurs
         */
        Route::middleware("isAdmin")->group(function() {
            Route::put("/users/{id}", "updateOther");  // Modifie les informations d'un autre utilisateur
            Route::delete("/users/{id}", "deleteOther");  // Supprime le compte d'un autre utilisateur
        });
    });
});