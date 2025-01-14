<?php

use App\Http\Controllers\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/books", [BooksController::class, "index"]);
Route::get("/books/{id}", [BooksController::class, "show"]);
Route::post("/books", [BooksController::class, "store"]);
