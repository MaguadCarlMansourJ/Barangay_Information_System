<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purok;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purok>
 */
class PurokFactory extends Factory
{
    protected $model = Purok::class;

    public function definition(): array
    {
        return [
            'name' => 'Purok ' . fake()->numberBetween(1, 15) . (fake()->boolean(50) ? chr(65 + fake()->numberBetween(0, 3)) : ''),
            'description' => fake()->randomElement(['Residential cluster', 'Main road area', 'Riverside community', 'Market area', 'Upland sitio']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
