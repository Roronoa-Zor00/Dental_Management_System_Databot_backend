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
        Schema::create('cases_status_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->unsignedBigInteger('p_case_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('case_status');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases_status_users');
    }
};
