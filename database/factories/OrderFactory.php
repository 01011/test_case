<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->numerify('89#########'),
            'email' => fake()->unique()->safeEmail(),
            'order_sum' => 1,
            'created_at' => \Carbon\Carbon::yesterday(),
            'updated_at' => \Carbon\Carbon::now()
        ];
    }
}
