<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'provider_id'           => Provider::factory(),
            'transaction_type'      => $this->faker->randomElement(['booking_payment','refund','payout','commission']),
            'amount'                => $this->faker->randomFloat(2, 10, 5000),
            'currency_code'         => $this->faker->currencyCode(),
            'payment_method'        => $this->faker->randomElement(['card','paypal','mpesa','bank_transfer']),
            'transaction_reference' => strtoupper($this->faker->bothify('TXN-#####-??')),
            'status'                => $this->faker->randomElement(['pending','completed','failed','refunded']),
        ];
    }
}
