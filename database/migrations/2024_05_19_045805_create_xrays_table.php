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
        Schema::create('xrays', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->string('file_name');
            $table->string('type')->nullable();
            $table->unsignedTinyInteger('order')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('p_case_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xrays');
    }
};
