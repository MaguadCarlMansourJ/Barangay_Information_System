<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(2),
            'location' => fake()->streetAddress,
            'event_date' => fake()->dateTimeBetween('-3 months', '+1 month')->format('Y-m-d'),
            'start_time' => fake()->time('H:i:s'),
            'end_time' => fake()->time('H:i:s'),
            'max_participants' => fake()->numberBetween(20, 500),
            'category' => fake()->randomElement(['Community Service', 'Social Event', 'Sports', 'Training', 'Health', 'Cultural', 'Meeting']),
            'status' => fake()->randomElement(['Upcoming', 'Ongoing', 'Completed']),
            'created_by' => User::factory()->createQuietly(['role' => 'Captain'])->id,
        ];
    }
}

