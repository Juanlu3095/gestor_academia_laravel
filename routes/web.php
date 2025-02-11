<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth.basic')->name('welcome');

/* Forms for login & register */
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/registro', [AuthController::class, 'registerForm'])->name('registro');

Route::post('/authenticate', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/prueba', [AuthController::class, 'prueba_user_model']);