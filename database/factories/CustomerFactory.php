<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'full_name' => fake()->name(),
            'first_name' =>fake()->name(),
            'last_name' =>fake()->name(),
            'email' =>fake()->email(),
            'document_type' => 1,
            'document' =>fake()->numberBetween(85455225, 99999999),
            'phone' =>fake()->phoneNumber(),
            'address' =>fake()->address(),
            'etapa' =>fake()->randomElement([1,2]),
            'is_ative' =>1,
            'is_client' =>true,
            'is_suplier' =>true,
        ];
    }
}
