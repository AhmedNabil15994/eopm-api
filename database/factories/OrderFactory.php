<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Entities\Order;
use Modules\User\Entities\User;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'subtotal' => fake()->randomFloat(3),
            'discount' => fake()->randomFloat(3,0),
            'tax' => fake()->randomFloat(3,0),
            'total' => fake()->randomFloat(3),
            'order_status_id' => $this->faker->unique()->randomElement([1,2,3,4,5]),
            'payment_method' => fake()->text(),
            'user_id' => User::factory(),
        ];
    }

}
