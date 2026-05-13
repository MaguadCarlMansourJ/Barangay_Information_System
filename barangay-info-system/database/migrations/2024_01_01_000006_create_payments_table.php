<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_request_id')->constrained()->onDelete('cascade');
            $table->string('or_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['Cash', 'GCash', 'PayMaya']);
            $table->string('reference_number')->nullable();
            $table->date('payment_date');
            $table->foreignId('received_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

