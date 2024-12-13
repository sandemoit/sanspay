<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'user_id' => rand(1, 10),
            'product_id' => rand(1, 10),
            'total_payment' => rand(1000, 100000),
            'payment_type' => rand(1, 2),
            'note' => $this->faker->paragraph(),
            'status_trx' => 'pending',
            'target' => $this->faker->word(),
        ];
    }
}
