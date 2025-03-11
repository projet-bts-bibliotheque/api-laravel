<?php

use App\Http\Controllers\EditorsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(EditorsController::class)->group(function() {
    Route::get("/editors", "index");
    Route::get("/editors/{id}", "show");

    Route::middleware("auth:sanctum")->group(function() {

        Route::middleware("isStaff")->group(function() {
            Route::post("/editors", "store");
            Route::put("/editors/{id}", "update");
            Route::delete("/editors/{id}", "destroy");
        });

    });
});