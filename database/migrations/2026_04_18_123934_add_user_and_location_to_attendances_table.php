<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('attendances', function (Blueprint $table) {

        // ✅ ADD ONLY IF NOT EXISTS
        if (!Schema::hasColumn('attendances', 'user_id')) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        }

        if (!Schema::hasColumn('attendances', 'school_id')) {
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        }

        if (!Schema::hasColumn('attendances', 'latitude')) {
            $table->double('latitude')->nullable();
        }

        if (!Schema::hasColumn('attendances', 'longitude')) {
            $table->double('longitude')->nullable();
        }

        if (!Schema::hasColumn('attendances', 'distance')) {
            $table->integer('distance')->nullable();
        }
    });
}

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['school_id']);

            $table->dropColumn([
                'user_id',
                'school_id',
                'distance'
            ]);
        });
    }
};