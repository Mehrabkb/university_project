<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('api')->group(function () {
    Route::post('/add/to/favorite', [\App\Http\Controllers\mainController::class, 'addToFavorite'])->name('add.to.favorite');
    Route::delete('/favorites/{id}', [\App\Http\Controllers\mainController::class, 'destroyFavorite'])->name('favorites.destroy');
});
Route::get('/dashboard', [\App\Http\Controllers\mainController::class , 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
