<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventCalendar>
 */
class EventCalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->sentence(),
            'location' => fake()->sentence(),
            'event_category' => fake()->randomElement(["Library/Books", "Community Engagement"]),
            'event_start_date' => fake()->date(),
            'event_start_time' => fake()->time(),
            'event_end_time' => fake()->time(),
            'is_active' => rand(0, 1)
        ];
    }
}
