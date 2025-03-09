<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AI\GroqController;
use App\Http\Controllers\AI\ChatController as AIChatController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\TokenAuthController;

Route::get('/', [ApiController::class, 'index']);

Route::post('login', [TokenAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [TokenAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user() ? new UserResource($request->user()) : null;
    });

    Route::prefix('user-resumes')->controller(ResumeController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{resumeId}', 'getResume');
        Route::put('/{resumeId}', 'update');
        Route::delete('/{resumeId}', 'destroy');
    });

    Route::get('/faqs', function (Request $request) {
        return response()->json(['data' => DB::table('f_a_q_s')->get()]);
    });

    Route::get('/agora-sessions', function (Request $request) {
        return response()->json(['data' => DB::table('agora_sessions')->get()]);
    });

    Route::get('/polls', function (Request $request) {
        return response()->json(["data" => DB::table('polls')->get()]);
    });

    Route::get('/groq', GroqController::class);

    Route::prefix('ai')->controller(AIChatController::class)->group(function () {
        Route::any('/chat', 'chat');
        Route::post('/memory-chat', 'chatWithMemory');
    });
});
