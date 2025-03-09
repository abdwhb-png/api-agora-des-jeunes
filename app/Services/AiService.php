<?php

namespace App\Services;

use App\Traits\AiServiceTrait;
use App\Interfaces\AiServiceInterface;
use App\Jobs\StoreAiUsage;
use Illuminate\Support\Arr;

class AiService
{
    use AiServiceTrait;

    public function __construct(public AiServiceInterface $ai) {}


    public function simpleChat(string $userInput, string $systemPrompt = ''): array
    {
        $messages = [
            ['role' => 'user', 'content' => $userInput]
        ];

        if ($systemPrompt) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        \Log::debug('aiservice', $messages);

        $result = $this->ai->simpleChat($messages);

        StoreAiUsage::dispatch(Arr::except($result, ['success', 'error', "output"]));

        return Arr::only($result, ['success', 'error', "output"]);
    }
}
