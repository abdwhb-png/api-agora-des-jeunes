<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
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
});
