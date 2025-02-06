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
        Schema::create('modification_receiveds', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->unsignedBigInteger('case_plan_id');
            $table->text('simulation_link_url');
            $table->text('ipr_chart');
            $table->longText('comments');
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modification_receiveds');
    }
};
