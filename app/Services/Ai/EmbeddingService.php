<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Log;

class EmbeddingService
{
    public function embedText(string $text): ?array
    {
        $text = trim($text);
        if (empty($text)) return null;

        // Use OpenAI if configured
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        if (!$apiKey) {
            Log::warning('OpenAI API key not configured; embeddings disabled.');
            return null;
        }

        try {
            if (class_exists('\OpenAI\OpenAI')) {
                $client = new \OpenAI\OpenAI($apiKey);
                $resp = $client->embeddings()->create([
                    'model' => 'text-embedding-3-small',
                    'input' => $text,
                ]);
                $vec = $resp['data'][0]['embedding'] ?? null;
                return is_array($vec) ? array_map('floatval', $vec) : null;
            }

            // Fallback: call OpenAI via HTTP client
            $http = new \GuzzleHttp\Client();
            $r = $http->post('https://api.openai.com/v1/embeddings', [
                'headers' => [
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'text-embedding-3-small',
                    'input' => $text,
                ],
            ]);

            $body = json_decode((string)$r->getBody(), true);
            $vec = $body['data'][0]['embedding'] ?? null;
            return is_array($vec) ? array_map('floatval', $vec) : null;
        } catch (\Throwable $e) {
            Log::error('Embedding request failed: '.$e->getMessage());
            return null;
        }
    }
}
