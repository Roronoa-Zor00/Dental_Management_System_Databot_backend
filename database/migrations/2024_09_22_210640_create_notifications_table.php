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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->string('title')->nullable();
            $table->string('body')->nullable();
            $table->string('url_action')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('is_admin_read')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
