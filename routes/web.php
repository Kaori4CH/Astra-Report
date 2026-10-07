<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect(auth()->user()->homeUrl())
        : redirect()->route('login-view');
});


Route::get('/login', [AuthController::class, 'loginview'])->name('login-view')->middleware('guest');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login-Post')->middleware('guest');
Route::get('/register', [AuthController::class, 'registerview'])->name('register-view')->middleware('guest');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register-Post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logoutPost'])->name('logout-Post')->middleware('auth');


Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::resource('dealers', DealerController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('tasks', TaskController::class);


    Route::name('reviews.')->prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{submission}', [ReviewController::class, 'show'])->name('show');
        Route::put('/{submission}', [ReviewController::class, 'update'])->name('update');
    });
});


Route::middleware(['auth', 'role:dealer'])->name('submissions.')->prefix('submissions')->group(function () {
    Route::get('/', [SubmissionController::class, 'index'])->name('index');
    Route::get('/{task}', [SubmissionController::class, 'show'])->name('show');
    Route::post('/{task}', [SubmissionController::class, 'store'])->name('store');
});
