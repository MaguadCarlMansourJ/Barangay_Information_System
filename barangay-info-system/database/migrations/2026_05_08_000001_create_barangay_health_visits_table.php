<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangay_health_visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resident_id')->constrained()->onDelete('cascade');

            $table->string('visit_number')->unique();
            $table->date('visit_date');
            $table->string('visit_time'); // keep as string to support HH:MM

            $table->enum('service_type', [
                'Consultation',
                'Pre-natal Check-up',
                'Post-natal Check-up',
                'Immunization',
                'Medical Certificate',
                'Family Planning',
                'Minor Treatment',
                'Others',
            ]);

            $table->text('complaints');
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();

            $table->boolean('is_urgent')->default(false);

            $table->foreignId('attended_by')->constrained('users');

            $table->enum('status', ['Scheduled', 'Done', 'Cancelled'])->default('Done');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangay_health_visits');
    }

};

