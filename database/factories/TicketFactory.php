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
            'ticket_event_id'=>fake()->randomDigitNotZero(),
            'ticket_name'=>fake()->name(),
            'ticket_price'=>fake()->randomNumber(),
            'ticket_quantity'=>fake()->randomDigit(),
            'ticket_available'=>fake()->randomNumber(0,1),
        ];
    }
}
