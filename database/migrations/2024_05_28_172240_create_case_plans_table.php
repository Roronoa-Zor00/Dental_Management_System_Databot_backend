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
        Schema::create('case_plans', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->unsignedBigInteger('p_case_id');
            $table->longText('ipr_chart');
            $table->longText('simulation_link_url');
            $table->longText('text_notes');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_plans');
    }
};
