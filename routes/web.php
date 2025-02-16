<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');

/* Forms for login & register */
Route::get('/login', [AuthController::class, 'loginForm'])->name('login'); // Auth redirige a la ruta de name 'login' si da un 401
Route::get('/registro', [AuthController::class, 'registerForm'])->name('registro');

Route::post('/authenticate', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

/* Perfil */
Route::get('/perfil', [UserController::class, 'index'])->middleware('auth')->name('perfil');
Route::patch('/users/{id}', [UserController::class, 'updateCurrentUserData'])->name('user.patch');
Route::delete('/users/{id}', [UserController::class, 'deleteCurrentUser'])->name('user.delete');