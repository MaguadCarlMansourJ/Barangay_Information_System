<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained();
            $table->string('request_number')->unique();
            $table->enum('status', ['Pending', 'Approved', 'Processing', 'Ready', 'Released', 'Rejected'])->default('Pending');
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->date('date_requested');
            $table->date('date_ready')->nullable();
            $table->date('date_released')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('released_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_requests');
    }
};

