<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

trait AiServiceTrait
{
    public static $aiService;

    public static function validateMessages(array $messages): array
    {
        $validated = Validator::make($messages, [
            '*.role' => 'sometimes|in:user,system',
            '*.content' => 'required|string',
        ])->validate();

        return $validated;
    }

    public static function parseResponse($answer, $inputText = null, $outputText = null, $tokensUsed = null, array $metadata = []): array
    {
        return [
            'success' => true,
            'output' => $answer,
            'input_text' => $inputText,
            'output_text' => $outputText,
            'tokens_used' => $tokensUsed,
            'metadata' => $metadata
        ];
    }

    /**
     * Handle errors and exceptions.
     *
     * @param \Throwable $exception
     * @return array
     */
    public static function handleError(\Throwable $exception): array
    {
        // Log the error for debugging purposes
        Log::channel('ai')->error(self::$aiService . ' AI Error: ' . $exception->getMessage(), [
            'exception' => $exception,
        ]);

        return [
            'success' => false,
            'error' => [
                'message' => 'An error occurred while processing your request.',
                'details' => $exception->getMessage(),
            ],
        ];
    }
}
