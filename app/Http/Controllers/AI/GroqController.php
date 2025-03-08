<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LucianoTonet\GroqLaravel\Facades\Groq;

class GroqController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return response()->json(Groq::models()->list());
    }
}
