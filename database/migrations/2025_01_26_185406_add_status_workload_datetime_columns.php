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
        Schema::table('cases_status_users', function (Blueprint $table) {
            $table->dateTime('work_start_datetime')->nullable();
            $table->dateTime('work_end_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases_status_users', function (Blueprint $table) {
            $table->dropColumn('work_start_datetime');
            $table->dropColumn('work_end_datetime');
        });
    }
};