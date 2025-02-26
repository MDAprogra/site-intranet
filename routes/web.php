<?php

use App\Http\Controllers\AccesIndicateursController;
use App\Http\Controllers\IndicateurController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/gestion-indicateur', [AccesIndicateursController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('access-indicateurs');
Route::put('/gestion-indicateur', [AccesIndicateursController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('indicateurs.update');

Route::get('/indicateur', [IndicateurController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('indicateur');

Route::get('/utilisateur', [UserController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('utilisateur');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
