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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('mobile_number');
            $table->string('profile_pic');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('company_id')->default(0);
            $table->unsignedTinyInteger('added_by')->default(0);
            $table->unsignedTinyInteger('is_account_owner')->default(0);
            $table->unsignedTinyInteger('is_active')->default(0);
            $table->unsignedTinyInteger('client_id')->default(0);
            $table->unsignedTinyInteger('sub_client_id')->default(0);
            $table->unsignedTinyInteger('created_by')->default(0);
            $table->datetime('last_login')->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('country_name')->nullable();
            $table->unsignedBigInteger('team_id')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('guid');
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
