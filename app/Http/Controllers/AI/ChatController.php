<?php

namespace App\Http\Controllers\AI;

use Illuminate\Http\Request;
use App\Services\GrokChatService;
use App\Http\Controllers\Controller;
use App\Services\AiService;

class ChatController extends Controller
{
    /**
     * Handle a simple chat request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'user_input' => 'required|string',
            'system_prompt' => 'nullable|string',
        ]);

        return response()->json(
            $aiService->simpleChat($validated['user_input'], $validated['system_prompt'] ?? '')
        );
    }

    /**
     * Handle a chat with memory request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chatWithMemory(Request $request)
    {
        $validated = $request->validate([
            'conversation_history' => 'sometimes|array',
            'user_input' => 'required|string',
        ]);

        return response()->json();
    }
}