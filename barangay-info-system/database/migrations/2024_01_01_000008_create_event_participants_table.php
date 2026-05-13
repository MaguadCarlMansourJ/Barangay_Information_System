<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('resident_id')->constrained();
            $table->enum('attendance_status', ['Registered', 'Attended', 'Absent'])->default('Registered');
            $table->timestamp('registered_at');
            $table->timestamps();
            $table->unique(['event_id', 'resident_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_participants');
    }
};

