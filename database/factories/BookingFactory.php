<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>fake()->uuid(),
            'name' =>fake()->name(),
            'phone_number'=>fake()->phoneNumber(),
            'email'=>fake()->email(),
            'total_price'=>fake()->numberBetween(100,200),
            'booking_date'=>fake()->date("Y-m-d"),
        ];
    }
}
