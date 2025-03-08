<?php

namespace App\Interfaces;

interface AiServiceInterface
{
    public function simpleChat(array $messages): array;
    public static function validateMessages(array $messages): array;
    public static function parseResponse($answer): array;
    public static function handleError(\Throwable $exception): array;
}
