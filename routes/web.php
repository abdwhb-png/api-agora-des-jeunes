<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('can:access_internal_api')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::controller(HomeController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/ai', 'aiManager')->name('ai.index');
            Route::put('/ai', 'aiUpdate')->name('ai.update');
        });

        Route::controller(ApiTokenController::class)->group(function () {
            Route::get('/user/api-tokens', 'index')->name('api-tokens.index');
            Route::post('/user/api-tokens', 'store')->name('api-tokens.store');
            Route::put('/user/api-tokens/{tokenId}', 'update')->name('api-tokens.update');
            Route::delete('/user/api-tokens/{tokenId}', 'destroy')->name('api-tokens.destroy');
        });
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
