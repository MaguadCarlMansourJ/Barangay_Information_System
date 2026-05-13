<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('resident_id')
                ->nullable()
                ->after('profile_photo')
                ->constrained('residents')
                ->nullOnDelete();
        });

        $residentId = DB::table('residents')->orderBy('id')->value('id');

        if ($residentId) {
            DB::table('users')
                ->where('role', 'Resident')
                ->whereNull('resident_id')
                ->update(['resident_id' => $residentId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('resident_id');
        });
    }
};
