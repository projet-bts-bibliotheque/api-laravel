<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post("/register", "register")->name("register");
    Route::post("/login", "login")->name("login");
});

Route::controller(BooksController::class)->group(function() {
    Route::get("/books", "index");
    Route::get("/books/{id}", "show");
});

Route::controller(AuthorsController::class)->group(function() {
    Route::get("/authors", "index");
    Route::get("/authors/{id}", "show");
});

Route::middleware("auth:sanctum")->group(function() {
    Route::get("me", [AuthController::class, "me"]);
    
    Route::post("/books", [BooksController::class, "store"]);
    Route::put("/books/{id}", [BooksController::class, "update"]);
    Route::delete("/books/{id}", [BooksController::class, "destroy"]);

    Route::post("/authors", [AuthorsController::class, "store"]);
    Route::put("/authors/{id}", [AuthorsController::class, "update"]);
    Route::delete("/authors/{id}", [AuthorsController::class, "destroy"]);
});