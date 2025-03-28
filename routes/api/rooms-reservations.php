<?php

use App\Http\Controllers\RoomsReservationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(RoomsReservationController::class)->group(function(){

    Route::get('/reservation/rooms', 'index');

    Route::middleware("auth:sanctum")->group(function(){
        Route::get('/reservation/rooms/me', 'showReservations');

        Route::post('/reservation/rooms', 'store');
        
        Route::delete('/reservation/rooms/{reservatioId}','destroy');

        Route::middleware("isStaff")->group(function(){
            
        })
    })
}