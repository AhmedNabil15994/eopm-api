<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Entities\Order;
use Modules\Transaction\Entities\Transaction;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'method' => fake()->text(),
            'payment_id' => fake()->randomNumber(),
            'tran_id' => fake()->randomNumber(),
            'result'    => $this->faker->unique()->randomElement([
                'pending',
                'failed',
                'success',
            ]),
            'post_date' => fake()->dateTime(),
            'ref'   => fake()->randomNumber(),
            'track_id' => fake()->randomNumber(),
            'auth' => fake()->randomNumber(),
            'transaction_id' => Order::factory(),
            'transaction_type'  => 'Modules\Order\Entities\Order',
        ];
    }

}
