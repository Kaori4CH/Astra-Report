<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\AreaController;
use app\Http\Controllers\DepartementController;
use app\Http\Controllers\DealerController;
use app\Http\Controllers\ReviewController;
Route::get('/', function () {
    return view('welcome');
});

Route::resource('departement', DepartementController::class);
Route::resource('dealer', DealerController::class);
Route::resource('area', AreaController::class);
Route::resource('review', ReviewController::class);
