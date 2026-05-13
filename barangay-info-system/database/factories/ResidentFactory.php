<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Resident;
use App\Models\Household;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    protected $model = Resident::class;

    public function definition(): array
    {
        // Ensure households exist first
        if (Household::count() === 0) {
            Household::factory()->count(10)->create();
        }

        $isRegisteredVoter = fake()->boolean(70);
        $isPwd = fake()->boolean(6);
        $isSoloParent = fake()->boolean(8);
        
        return [
            'household_id' => Household::inRandomOrder()->first()->id,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'middle_name' => fake()->lastName,
            'suffix' => fake()->randomElement(['', 'Jr', 'Sr', 'II', 'III']),
            'birthdate' => fake()->dateTimeBetween('-80 years', '-1 year'),
            'place_of_birth' => fake()->randomElement(['Davao City', 'Digos City, Davao del Sur', 'Tagum City, Davao del Norte', 'Kidapawan City, Cotabato', 'General Santos City']),
            'citizenship' => 'Filipino',
            'religion' => fake()->randomElement(['Roman Catholic', 'Islam', 'Iglesia ni Cristo', 'Seventh-day Adventist', 'Born Again Christian', 'None']),
            'educational_attainment' => fake()->randomElement(['Elementary Level', 'Elementary Graduate', 'High School Level', 'High School Graduate', 'Senior High School Graduate', 'Vocational', 'College Level', 'College Graduate']),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'civil_status' => fake()->randomElement(['Single', 'Married', 'Widowed', 'Divorced', 'Separated']),
            'relationship_to_household_head' => fake()->randomElement(['Head', 'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Relative', 'Boarder']),
            'date_of_residency' => fake()->dateTimeBetween('-35 years', 'now'),
            'is_registered_voter' => $isRegisteredVoter,
            'voter_precinct_number' => $isRegisteredVoter ? fake()->numerify('0###') . fake()->randomLetter : null,
            'occupation' => fake()->jobTitle,
            'contact_number' => '09' . fake()->numerify('#########'),
            'email' => fake()->safeEmail,
            'philhealth_id' => 'PH'.fake()->unique()->numerify('###########'),
            'is_senior_citizen' => fake()->boolean(12),
            'is_pwd' => $isPwd,
            'pwd_id_number' => $isPwd ? fake()->numerify('PWD-####-###') : null,
            'is_solo_parent' => $isSoloParent,
            'solo_parent_id_number' => $isSoloParent ? fake()->numerify('SP-####-###') : null,
            'is_4ps_beneficiary' => fake()->boolean(18),
            'is_indigenous_person' => fake()->boolean(5),
            'is_active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
