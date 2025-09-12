<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('transactions')) return;
        if (! Schema::hasTable('providers')) return;

        $provider = DB::table('providers')->inRandomOrder()->first();
        if (! $provider || ! isset($provider->id)) return;
        $providerId = $provider->id;

        // Ensure provider still exists
        if (! DB::table('providers')->where('id', $providerId)->exists()) return;

        $samples = [
            ['transaction_reference' => 'TXN_001_US', 'transaction_type' => 'booking_payment', 'amount' => 150, 'currency_code' => 'USD', 'payment_method' => 'card', 'status' => 'completed'],
            ['transaction_reference' => 'TXN_002_AU', 'transaction_type' => 'booking_refund', 'amount' => 75, 'currency_code' => 'AUD', 'payment_method' => 'card', 'status' => 'refunded'],
            ['transaction_reference' => 'TXN_003_KE', 'transaction_type' => 'booking_payment', 'amount' => 5000, 'currency_code' => 'KES', 'payment_method' => 'mpesa', 'status' => 'completed'],
        ];

        foreach ($samples as $s) {
            // Verify provider still exists before each insert
            if (! DB::table('providers')->where('id', $providerId)->exists()) {
                continue;
            }

            $row = [
                'transaction_reference' => $s['transaction_reference'],
                'provider_id' => $providerId,
                'transaction_type' => $s['transaction_type'],
                'amount' => $s['amount'],
                'currency_code' => $s['currency_code'],
                'payment_method' => $s['payment_method'],
                'status' => $s['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            try {
                DB::table('transactions')->updateOrInsert([
                    'transaction_reference' => $s['transaction_reference']
                ], $row);
            } catch (\Throwable $e) {
                // ignore FK or other insert errors and continue
            }
        }
    }
}
