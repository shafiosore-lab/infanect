<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'provider_id' => 1,
                'transaction_type' => 'booking_payment',
                'amount' => 150.00,
                'currency_code' => 'USD',
                'payment_method' => 'card',
                'transaction_reference' => 'TXN_001_US',
                'status' => 'completed',
            ],
            [
                'provider_id' => 1,
                'transaction_type' => 'booking_payment',
                'amount' => 200.00,
                'currency_code' => 'USD',
                'payment_method' => 'paypal',
                'transaction_reference' => 'TXN_002_US',
                'status' => 'completed',
            ],
            [
                'provider_id' => 2,
                'transaction_type' => 'booking_payment',
                'amount' => 5000.00,
                'currency_code' => 'KES',
                'payment_method' => 'mpesa',
                'transaction_reference' => 'TXN_003_KE',
                'status' => 'completed',
            ],
            [
                'provider_id' => 2,
                'transaction_type' => 'booking_payment',
                'amount' => 3000.00,
                'currency_code' => 'KES',
                'payment_method' => 'card',
                'transaction_reference' => 'TXN_004_KE',
                'status' => 'completed',
            ],
            [
                'provider_id' => 3,
                'transaction_type' => 'booking_payment',
                'amount' => 25000.00,
                'currency_code' => 'JPY',
                'payment_method' => 'card',
                'transaction_reference' => 'TXN_005_JP',
                'status' => 'completed',
            ],
            [
                'provider_id' => 4,
                'transaction_type' => 'booking_payment',
                'amount' => 120.00,
                'currency_code' => 'EUR',
                'payment_method' => 'paypal',
                'transaction_reference' => 'TXN_006_DE',
                'status' => 'completed',
            ],
            [
                'provider_id' => 5,
                'transaction_type' => 'booking_payment',
                'amount' => 8000.00,
                'currency_code' => 'INR',
                'payment_method' => 'card',
                'transaction_reference' => 'TXN_007_IN',
                'status' => 'completed',
            ],
            [
                'provider_id' => 1,
                'transaction_type' => 'refund',
                'amount' => -50.00,
                'currency_code' => 'USD',
                'payment_method' => 'card',
                'transaction_reference' => 'REF_001_US',
                'status' => 'completed',
            ],
        ];

        foreach ($transactions as $transaction) {
            \App\Models\Transaction::create($transaction);
        }
    }
}
