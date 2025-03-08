<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LucianoTonet\GroqLaravel\Facades\Groq;

class HomeController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function aiManager()
    {
        return Inertia::render('AIManager', [
            'ai_setting' => ai_setting(),
            'groq_models' => Groq::models()->list(),
        ]);
    }

    public function aiUpdate(Request $request)
    {
        $validated = $request->validate([
            'groq_model' => 'required|string',
            'groq_api_key' => 'required|string',
        ]);

        DB::table('ai_settings')->updateOrInsert([
            'id' => optional(ai_setting())->id,
        ], [
            ...$validated,
            'updated_at' => now(),
        ]);

        return back(303)->with("status", "AI settings updated.");
    }
}
