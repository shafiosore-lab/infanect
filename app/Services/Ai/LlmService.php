<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Log;

class LlmService
{
    public function generateAnswer(string $question, string $context): ?string
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        if (!$apiKey) {
            Log::warning('OpenAI API key not configured; LLM disabled.');
            return null;
        }

        $system = "You are an assistant that answers questions using the provided context from user-uploaded documents. If the answer is not in the context, say you don't know.";
        $prompt = "Context:\n" . ($context ?: '[no context available]') . "\n\nQuestion: {$question}\nAnswer:";

        try {
            if (class_exists('\OpenAI\OpenAI')) {
                $client = new \OpenAI\OpenAI($apiKey);
                $resp = $client->chat()->create([
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 512,
                ]);
                return trim($resp['choices'][0]['message']['content'] ?? '');
            }

            $http = new \GuzzleHttp\Client();
            $r = $http->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 512,
                ],
            ]);

            $body = json_decode((string)$r->getBody(), true);
            return trim($body['choices'][0]['message']['content'] ?? '');
        } catch (\Throwable $e) {
            Log::error('LLM request failed: '.$e->getMessage());
            return null;
        }
    }
}
