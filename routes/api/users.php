<?php

use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post("/register", "register")->name("register");
    Route::post("/login", "login")->name("login");
    
    Route::get("/password-reset/{token}", function(string $token) {
        return response()->json([
            "token" => $token
        ]);
    })->name('password.reset');

    Route::post("/forgot-password", "forgotPassword");
    Route::post("/reset-password", "resetPassword");


    Route::middleware("auth:sanctum")->group(function() {
        Route::get("/me", "me");
        Route::delete("/me", "delete");
        Route::put("/me", "update");

        Route::post('/email/verification-notification', "sendVerifyEmail")->middleware(['throttle:6,1'])->name('verification.send');
        Route::get('/email/verify/{id}/{hash}', "verifyEmail")->name('verification.verify');

        Route::middleware("isStaff")->group(function() {
            Route::get("/users", "index");
            Route::post("/forgot-password/{id}", "forgotPasswordOther");
        });

        Route::middleware("isAdmin")->group(function() {
            Route::put("/users/{id}", "updateOther");
            Route::delete("/users/{id}", "deleteOther");
        });
    });
});