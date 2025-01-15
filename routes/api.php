<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/books", [BooksController::class, "index"]);
Route::get("/books/{id}", [BooksController::class, "show"]);
Route::post("/books", [BooksController::class, "store"]);
Route::put("/books/{id}", [BooksController::class, "update"]);
Route::delete("/books/{id}", [BooksController::class, "destroy"]);

Route::get("/authors", [AuthorsController::class, "index"]);
Route::get("/authors/{id}", [AuthorsController::class, "show"]);
Route::post("/authors", [AuthorsController::class, "store"]);
Route::put("/authors/{id}", [AuthorsController::class, "update"]);
Route::delete("/authors/{id}", [AuthorsController::class, "destroy"]);
