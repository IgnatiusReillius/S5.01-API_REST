<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id_user}', [UserController::class, 'find']);
Route::put('/users/{id_user}', [UserController::class, 'update']);
Route::delete('/users', [UserController::class, 'destroyAllUsers']);
Route::delete('/users/{id_user}', [UserController::class, 'destroyById']);

