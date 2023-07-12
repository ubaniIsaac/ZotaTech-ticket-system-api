<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'short_link' => fake()->url(), // 'https://www.google.com
            'date' => fake()->date(),
            'location' => fake()->address(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'time' => fake()->time(),
        
        ];
    }
}
