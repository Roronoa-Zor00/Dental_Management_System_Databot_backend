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
        Schema::create('patient_cases', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->string('name');
            $table->string('email');
            $table->longText('extraction');
            $table->longText('attachments');
            $table->string('case_id');
            $table->string('age');
            $table->string('gender');
            $table->string('ipr');
            $table->longText('chief_complaint');
            $table->longText('treatment_plan');
            $table->string('stl_upper_file')->nullable();
            $table->string('stl_lower_file')->nullable();
            $table->string('stl_byte_scan_file')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('assign_to')->default(0);
            $table->unsignedBigInteger('created_by_admin')->default(0);
            $table->unsignedBigInteger('planner_id')->default(0);
            $table->unsignedBigInteger('qa_id')->default(0);
            $table->unsignedBigInteger('is_priority')->default(0);
            $table->unsignedBigInteger('post_processing_id')->default(0);
            $table->string('expected_time')->nullable();
            $table->string('tooth_label_format')->nullable();
            $table->unsignedTinyInteger('case_version')->default(1);
            $table->string('setup_type')->nullable();
            $table->unsignedTinyInteger('scan_version')->default(0);
            $table->string('container_file_by_post_processing')->nullable();
            $table->unsignedBigInteger('sub_client_id')->default(0);
            $table->unsignedBigInteger('client_id')->default(0);
            $table->unsignedTinyInteger('verified_by_client')->default(0);
            $table->timestamp('start_date_time')->nullable();
            $table->integer('start_date_time_timestamp_string')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_cases');
    }
};
