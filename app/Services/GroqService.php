<?php

namespace App\Services;

use App\Traits\AiServiceTrait;
use App\Interfaces\AiServiceInterface;
use LucianoTonet\GroqLaravel\Facades\Groq;
use LucianoTonet\GroqPHP\GroqException;

class GroqService implements AiServiceInterface
{
    use AiServiceTrait;

    protected $groq;
    protected $model;

    public function __construct()
    {
        self::$aiService = 'GroqPHP';
        $this->model = ai_setting('groq_model') ?? 'mixtral-8x7b-32768';
        $this->groq = new Groq(config('groq.api_key', ai_setting('groq_api_key')));
    }

    public function simpleChat(array $messages): array
    {
        try {
            $validatedMessages = $this->validateMessages($messages);
            $params = [
                'model'    => $this->model,
                'messages' => $validatedMessages,
            ];
            $chatCompletion = $this->groq->chat()->completions()->create($params);

            return $this->parseResponse(
                $chatCompletion['choices'][0]['message']['content'],
                $params,
                $chatCompletion['choices'],
                $chatCompletion['usage']['totla_tokens'],
                $chatCompletion
            );
        } catch (GroqException $e) {
            return $this->handleError($e);
        }
    }
}
