<?php

use App\Http\Controllers\AuthorsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthorsController::class)->group(function() {
    Route::get("/authors", "index");
    Route::get("/authors/{id}", "show");

    Route::middleware("auth:sanctum")->group(function() {

        Route::middleware("isStaff")->group(function() {
            Route::post("/authors", "store");
            Route::put("/authors/{id}", "update");
            Route::delete("/authors/{id}", "destroy");
        });

    });
});