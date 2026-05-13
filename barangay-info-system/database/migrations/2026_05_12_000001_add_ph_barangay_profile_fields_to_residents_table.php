<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->after('birthdate');
            $table->string('citizenship')->default('Filipino')->after('place_of_birth');
            $table->string('religion')->nullable()->after('citizenship');
            $table->string('educational_attainment')->nullable()->after('religion');
            $table->string('relationship_to_household_head')->nullable()->after('civil_status');
            $table->date('date_of_residency')->nullable()->after('relationship_to_household_head');
            $table->boolean('is_registered_voter')->default(false)->after('date_of_residency');
            $table->string('voter_precinct_number')->nullable()->after('is_registered_voter');
            $table->boolean('is_senior_citizen')->default(false)->after('philhealth_id');
            $table->boolean('is_pwd')->default(false)->after('is_senior_citizen');
            $table->string('pwd_id_number')->nullable()->after('is_pwd');
            $table->boolean('is_solo_parent')->default(false)->after('pwd_id_number');
            $table->string('solo_parent_id_number')->nullable()->after('is_solo_parent');
            $table->boolean('is_4ps_beneficiary')->default(false)->after('solo_parent_id_number');
            $table->boolean('is_indigenous_person')->default(false)->after('is_4ps_beneficiary');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth',
                'citizenship',
                'religion',
                'educational_attainment',
                'relationship_to_household_head',
                'date_of_residency',
                'is_registered_voter',
                'voter_precinct_number',
                'is_senior_citizen',
                'is_pwd',
                'pwd_id_number',
                'is_solo_parent',
                'solo_parent_id_number',
                'is_4ps_beneficiary',
                'is_indigenous_person',
            ]);
        });
    }
};
