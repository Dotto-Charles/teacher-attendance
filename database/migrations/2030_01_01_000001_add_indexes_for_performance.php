<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->index(['school_id', 'role', 'status']);
            $table->index(['ward_id', 'role', 'status']);
            $table->index(['council_id', 'role', 'status']);
            $table->index('check_number');
            $table->index('email');
        });

        // Indexes for attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['school_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
        });

        // Indexes for schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->index('ward_id');
        });

        // Indexes for wards table
        Schema::table('wards', function (Blueprint $table) {
            $table->index('council_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'role', 'status']);
            $table->dropIndex(['ward_id', 'role', 'status']);
            $table->dropIndex(['council_id', 'role', 'status']);
            $table->dropIndex(['check_number']);
            $table->dropIndex(['email']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['school_id', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['ward_id']);
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->dropIndex(['council_id']);
        });
    }
};