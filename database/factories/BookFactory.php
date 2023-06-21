<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name(),
            'genre' => $this->faker->word,
            'total_pages' => $this->faker->numberBetween(10, 100),

            /** status_id should not be created randomly, please refer to BookStatus::ENUMS */
            'status_id' => $this->faker->numberBetween(1, 3),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
