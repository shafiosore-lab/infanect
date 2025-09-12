<?php

namespace App\Services\Payments;

class PaymentGatewayStub
{
    public function createPayout(array $data)
    {
        // stubbed response
        return [
            'status' => 'queued',
            'payout_id' => 'p_' . uniqid(),
            'meta' => $data,
        ];
    }
}
