<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/refresh', [AuthenticationController::class, 'refresh']);
    Route::get('/me', [AuthenticationController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id_user}', [UserController::class, 'find']);
    Route::put('/users/{id_user}', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroyAllUsers']);
    Route::delete('/users/{id_user}', [UserController::class, 'destroyById']);
    
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id_book}', [BookController::class, 'find']);
    Route::put('/books/{id_book}', [BookController::class, 'update']);
    Route::delete('/books', [BookController::class, 'destroyAll']);
    Route::delete('/books/{id_book}', [BookController::class, 'destroy']);

    Route::post('/users/{id_user}/books/{id_book}/reviews', [ReviewController::class, 'store']);
    Route::get('/users/books/reviews', [ReviewController::class, 'getAllReviews']);
    Route::get('/users/books/{id_book}/reviews', [ReviewController::class, 'getBookReviews']);
    Route::get('/users/{id_user}/reviews', [ReviewController::class, 'getUserReviews']);
    Route::get('/users/{id_user}/books/{id_book}/reviews', [ReviewController::class, 'show']);
    Route::put('/users/{id_user}/books/{id_book}/reviews', [ReviewController::class, 'update']);
    Route::delete('/users/books/reviews', [ReviewController::class, 'destroyAll']);
    Route::delete('/users/books/{id_book}/reviews', [ReviewController::class, 'destroyBookReviews']);
    Route::delete('/users/{id_user}/reviews', [ReviewController::class, 'destroyUserReviews']);
    Route::delete('/users/{id_user}/books/{id_book}/reviews', [ReviewController::class, 'destroy']);
});


