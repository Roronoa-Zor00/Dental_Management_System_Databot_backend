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
        Schema::create('need_more_infos', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->unsignedBigInteger('p_case_id');
            $table->longText('notes');
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
        Schema::dropIfExists('need_more_infos');
    }
};
