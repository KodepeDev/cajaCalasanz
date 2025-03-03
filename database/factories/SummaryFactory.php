<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Summary>
 */
class SummaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'date' => fake()->date('Y-m-d', now()),
            'concept' => $this->faker->sentence(10),
            'type' => 'add',
            'status' => 'PAID',
            'amount' =>$this->faker->randomDigit(),
            'tax' =>$this->faker->randomDigit(),
            'future' => '1',
            'account_id' => fake()->numberBetween(1, 10),
            'category_id' => fake()->numberBetween(7, 11),
            'id_attr' => null,
            'id_transfer' => null,
            'tours_id' => null,
            'id_attr_tours' => null,
            'user_id' => 1,
            'customer_id' => fake()->numberBetween(1, 10)
        ];
    }
}
