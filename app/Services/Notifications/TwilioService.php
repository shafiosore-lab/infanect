<?php

namespace App\Services\Notifications;

class TwilioService
{
    public function sendSms($to, $message)
    {
        // Implement Twilio API later; for now log
        logger()->info('Sending SMS', compact('to','message'));
        return ['status' => 'queued'];
    }

    public function sendWhatsApp($to, $message)
    {
        logger()->info('Sending WhatsApp', compact('to','message'));
        return ['status' => 'queued'];
    }
}
