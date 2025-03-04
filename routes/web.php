<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('can:access_internal_api')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard', [...app(ApiTokenController::class)->getTokensData()]);
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
        Route::put('/user/api-tokens/{tokenId}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
        Route::delete('/user/api-tokens/{tokenId}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
