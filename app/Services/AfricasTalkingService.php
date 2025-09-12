<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AfricasTalkingService
{
    protected $username;
    protected $apiKey;
    protected $from;

    public function __construct()
    {
        $this->username = env('AT_USERNAME');
        $this->apiKey = env('AT_API_KEY');
        $this->from = env('AT_FROM', 'InfaNect');
    }

    /**
     * Send SMS via Africa's Talking (stubbed)
     * Returns array with status and raw response for logging
     */
    public function sendSMS(string $to, string $message): array
    {
        try {
            // Africa's Talking messaging endpoint
            $endpoint = 'https://api.africastalking.com/version1/messaging';

            $response = Http::withHeaders([
                'apiKey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->asForm()->post($endpoint, [
                'username' => $this->username,
                'to' => $to,
                'message' => $message,
                'from' => $this->from,
            ]);

            $body = $response->json();
            Log::info('AT SMS send', ['to' => $to, 'response' => $body]);

            return ['ok' => $response->successful(), 'response' => $body];
        } catch (\Exception $e) {
            Log::error('AT SMS error', ['error' => $e->getMessage(), 'to' => $to]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send WhatsApp via Africa's Talking (placeholder/stub)
     * Note: WhatsApp support via AT may require business account and templates.
     */
    public function sendWhatsApp(string $to, string $message): array
    {
        // Placeholder: implement actual WhatsApp Business API or AT Conversations when available
        Log::info('AT WhatsApp send (stub)', ['to' => $to, 'message' => $message]);
        return ['ok' => true, 'response' => ['status' => 'stubbed']];
    }
}
