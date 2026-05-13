<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->string('blotter_number')->unique();
            $table->enum('type', ['Complaint', 'Incident', 'Dispute']);
            $table->text('description');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('incident_location');
            $table->enum('status', ['Open', 'Under Investigation', 'Resolved', 'Closed'])->default('Open');
            $table->text('resolution')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->date('resolved_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blotters');
    }
};

