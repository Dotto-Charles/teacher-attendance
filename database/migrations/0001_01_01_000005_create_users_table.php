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

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('check_number')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();

            $table->enum('sex', ['male', 'female']);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // roles
            $table->enum('role', [
                'admin',
                'district_officer',
                'ward_officer',
                'head_teacher',
                'teacher'
            ])->default('teacher');

            // status
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            // relations
            $table->foreignId('school_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->foreignId('ward_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};