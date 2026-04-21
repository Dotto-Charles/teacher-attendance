<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 👤 PERSONAL INFO
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            // 🆔 UNIQUE CHECK NUMBER
            $table->string('check_number')->unique();

            // 📧 CONTACT
            $table->string('email')->unique();
            $table->string('phone')->unique();

            // ⚧ SEX
            $table->enum('sex', ['male', 'female']);

            // 🔐 AUTH
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // 👨‍🏫 SYSTEM ROLE
            $table->string('role',['head_master','admin','teacher'])->default('teacher');

            // ✅ APPROVAL
            $table->string('status')->default('pending');

            // 🏫 SCHOOL RELATION
            $table->unsignedBigInteger('school_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        // PASSWORD RESET
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')->nullable()->index();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};