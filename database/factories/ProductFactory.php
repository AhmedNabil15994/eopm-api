<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Product\Entities\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->randomFloat(),
            'qty' => fake()->randomNumber(),
            'sku' => fake()->uuid(),
            'status'    => 1
        ];
    }

}
