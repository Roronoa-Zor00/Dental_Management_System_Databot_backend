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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->integer('user_id');
            $table->string('user_type')->nullable();
            $table->string('entity');
            $table->string('action');
            $table->string('post_words');
            $table->string('route_name');
            $table->string('route_id');
            $table->text('url');
            $table->string('module')->nullable();
            $table->integer('comment_id')->nullable();
            $table->integer('insurance_type')->nullable();
            $table->longText('request_data')->nullable();
            $table->unsignedTinyInteger('show_team_activity')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
