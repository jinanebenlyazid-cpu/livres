<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EmpruntController;
use App\Http\Controllers\Api\LivreController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/livres', [LivreController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('api.token')->group(function (): void {
    Route::get('/emprunts', [EmpruntController::class, 'index']);
    Route::post('/emprunts', [EmpruntController::class, 'store']);
    Route::patch('/emprunts/{emprunt}/retourner', [EmpruntController::class, 'returnBook']);
    Route::delete('/emprunts/{emprunt}', [EmpruntController::class, 'destroy']);
});
