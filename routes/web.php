<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

/* Alumnos */
Route::get('/alumnos', [StudentController::class, 'index'])->middleware('auth')->name('alumnos.index');
Route::get('/alumnos/{id}', [StudentController::class, 'show'])->name('alumnos.show');
Route::post('/alumnos', [StudentController::class, 'create'])->name('alumnos.create');
Route::put('/alumnos/{id}', [StudentController::class, 'update'])->name('alumnos.put');
Route::delete('/alumnos/{id}', [StudentController::class, 'delete'])->name('alumnos.delete');

/* Profesores */
Route::get('/profesores', [TeacherController::class, 'index'])->middleware('auth')->name('profesores.index');
Route::get('/profesores/{id}', [TeacherController::class, 'show'])->name('profesores.show');
Route::post('/profesores', [TeacherController::class, 'create'])->name('profesores.create');
Route::put('/profesores/{id}', [TeacherController::class, 'update'])->name('profesores.put');
Route::delete('/profesores/{id}', [TeacherController::class, 'delete'])->name('profesores.delete');
// Ruta para una página en la que se ve en detalle a un alumno/profesor en concreto ??

/* Incidencias */

/* Cursos */
Route::get('/cursos', [CourseController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{id}', [CourseController::class, 'details'])->name('cursos.details');
Route::post('/cursos', [CourseController::class, 'create'])->name('cursos.create');
Route::put('/cursos/{id}', [CourseController::class, 'update'])->name('cursos.update');
Route::delete('/cursos/{id}', [CourseController::class, 'delete'])->name('cursos.delete');

/* CursosAlumnos */
Route::get('/cursoalumno/{id}/alumnos', [CourseStudentController::class, 'getAvailableStudents'])->name('cursoalumno.alumnos');
Route::post('/cursoalumno', [CourseStudentController::class, 'addStudentToCourse'])->name('cursoalumno.create');
Route::delete('/cursoalumno/{id}', [CourseStudentController::class, 'deleteStudentFromCourse'])->name('cursoalumno.delete');