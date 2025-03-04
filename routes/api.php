<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/faqs', function (Request $request) {
        return response()->json(DB::table('faqs')->get());
    });

    Route::get('/agora-sessions', function (Request $request) {
        return response()->json(DB::table('agora_sessions')->get());
    });

    Route::get('/polls', function (Request $request) {
        return response()->json(DB::table('polls')->get());
    });
});
