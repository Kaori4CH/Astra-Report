<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('Departements', DepartementController::class);
Route::resource('Dealers', DealerController::class);
Route::resource('Areas', AreaController::class);
Route::resource('Reviews', ReviewController::class);
