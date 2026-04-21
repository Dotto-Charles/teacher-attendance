<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolAndStatusToUsers extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'school_id')) {
                $table->foreignId('school_id')
                      ->nullable()
                      ->constrained()
                      ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending','approved'])
                      ->default('pending');
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'school_id')) {
                $table->dropColumn('school_id');
            }

            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }

        });
    }
}