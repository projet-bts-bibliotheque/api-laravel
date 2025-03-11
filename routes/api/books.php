<?php

use App\Http\Controllers\BooksController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(BooksController::class)->group(function() {
    Route::get("/books", "index");
    Route::get("/books/{id}", "show");

    Route::middleware("auth:sanctum")->group(function() {

        Route::middleware("isStaff")->group(function() {
            Route::post("/books", "store");
            Route::put("/books/{id}", "update");
            Route::delete("/books/{id}", "destroy");
        });

    });
});