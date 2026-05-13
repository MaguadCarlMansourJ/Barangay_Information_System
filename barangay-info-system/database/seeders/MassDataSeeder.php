<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purok;
use App\Models\Household;
use App\Models\Resident;

class MassDataSeeder extends Seeder
{
    public function run(): void
    {
        Purok::factory()->count(10)->create();
        Household::factory()->count(300)->create();
        Resident::factory()->count(1378)->create();
        
        echo "SUCCESS: 10 Puroks, 300 Households, 1378 Residents created!\n";
    }
}

