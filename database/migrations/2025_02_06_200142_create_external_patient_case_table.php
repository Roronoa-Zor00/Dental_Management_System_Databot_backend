<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('external_patient_cases', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid')->unique();
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('client_id')->default(0);
            $table->dateTime('case_datetime')->nullable();
            $table->string('software_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_patient_case');
    }
};
