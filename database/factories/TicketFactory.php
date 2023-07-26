<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id'=>fake()->uuid(),
            'ticket_type'=>fake()->randomDigit(),
            'price'=>fake()->randomDigit(),
            'quantity'=>fake()->randomNumber(),
            'available'=>fake()->randomDigit(),
        ];
    }
}
