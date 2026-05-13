<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blotter_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_id')->constrained()->onDelete('cascade');
            $table->foreignId('resident_id')->constrained();
            $table->enum('party_type', ['Complainant', 'Respondent', 'Witness']);
            $table->text('statement')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blotter_parties');
    }
};

