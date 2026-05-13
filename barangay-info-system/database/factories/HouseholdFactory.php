<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Household;
use App\Models\Purok;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{
    protected $model = Household::class;

    public function definition(): array
    {
        return [
            'purok_id' => Purok::inRandomOrder()->value('id') ?? Purok::factory()->create()->id,
            'house_number' => 'Blk '.fake()->numberBetween(1,50). ' Lot '.fake()->numberBetween(1,100),
            'address' => fake()->streetAddress . ', Barangay Poblacion, Davao City',
            'member_count' => fake()->numberBetween(1,8),
        ];
    }
}
