<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('council_id')->nullable()->after('ward_id');

            $table->foreign('council_id')
                ->references('id')
                ->on('councils')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['council_id']);
            $table->dropColumn('council_id');
        });
    }
};