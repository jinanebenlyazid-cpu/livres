<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LivreController::class, 'home'])->name('home');
Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');
Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/mes-emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::post('/livres/{livre}/emprunter', [EmpruntController::class, 'store'])->name('emprunts.store');
    Route::patch('/emprunts/{emprunt}/retourner', [EmpruntController::class, 'returnBook'])->name('emprunts.return');
    Route::delete('/emprunts/{emprunt}', [EmpruntController::class, 'destroy'])->name('emprunts.destroy');
});
