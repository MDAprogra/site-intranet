<?php

use App\Http\Controllers\AccesIndicateursController;
use App\Http\Controllers\IndicateurController;
use App\Http\Controllers\AssistantCommercialController;
use App\Http\Controllers\Indicateurs\DevisController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CreateUserController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('accueil');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/slideshow', [MediaController::class, 'showSlideshow'])->name('slideshow');


//Route::get('/gestion-indicateur', [AccesIndicateursController::class, 'index'])
//    ->middleware(['auth', 'verified'])
//    ->name('access-indicateurs');
//
//Route::put('/gestion-indicateur', [AccesIndicateursController::class, 'update'])
//    ->middleware(['auth', 'verified'])
//    ->name('indicateurs.update');
//
//Route::get('/indicateur', [IndicateurController::class, 'index'])
//    ->middleware(['auth', 'verified'])
//    ->name('indicateur');
//
//Route::get('/indicateur/{indicateur}', [IndicateurController::class, 'show'])
//    ->middleware(['auth', 'verified'])
//    ->name('indicateur.show');

Route::get('/indicateur/devis', [DevisController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('indicateur.devis');
Route::get('/indicateur/pao', [PaoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('indicateur.pao');
Route::get('/indicateur/assistant-commercial', [AssistantCommercialController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('indicateur.asscom');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/utilisateur', [UserController::class, 'index'])
        ->name('utilisateur');
    Route::get('/utilisateur/{user}/edit', [UserController::class, 'edit'])
        ->name('utilisateur.edit');
    Route::put('/utilisateur/{user}', [UserController::class, 'update'])
        ->name('utilisateur.update');

    Route::get('/gestion-indicateur', [AccesIndicateursController::class, 'index'])
        ->name('access-indicateurs');
    Route::put('/gestion-indicateur', [AccesIndicateursController::class, 'update'])
        ->name('indicateurs.update');

    Route::get('/indicateur', [IndicateurController::class, 'index'])
        ->name('indicateur');
//    Route::get('/indicateur/{indicateur}', [IndicateurController::class, 'show'])
//        ->name('indicateur.show');

    Route::get('/indicateur/devis', [DevisController::class, 'index'])
        ->name('indicateur.devis');
    Route::get('/utilisateur', [UserController::class, 'index'])
        ->name('utilisateur');

    Route::get('/utilisateur/{user}/edit', [UserController::class, 'edit'])
        ->name('utilisateur.edit');
    Route::put('/utilisateur/{user}', [UserController::class, 'update'])
        ->name('utilisateur.update');


});

//Route::get('/create-user', [CreateUserController::class, 'create'])
//    ->middleware(['auth', 'verified', 'role:admin'])
//    ->name('create-user');
//
//Route::post('/create-user', [CreateUserController::class, 'store'])
//    ->middleware(['auth', 'verified', 'role:admin']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/create-user', [CreateUserController::class, 'create'])->name('create-user');
    Route::post('/create-user', [CreateUserController::class, 'store']);

    Route::get('/manage', [MediaController::class, 'showManagePage'])->name('manage');
    Route::post('/upload', [MediaController::class, 'uploadMedia'])->name('media.upload');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/media/{id}/move-up', [MediaController::class, 'moveUp'])->name('media.moveUp');
    Route::get('/media/{id}/move-down', [MediaController::class, 'moveDown'])->name('media.moveDown');

});

require __DIR__ . '/auth.php';
