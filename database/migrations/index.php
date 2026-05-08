<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Performance indexes migration.
 * Inaboresha queries za kawaida zinazotumiwa sana.
 * Inashughulikia: attendances, users, schools, transfers, wards.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ATTENDANCES ──────────────────────────────────────────────
        // Query: WHERE school_id=? AND DATE(created_at)=?  (check-in leo)
        Schema::table('attendances', function (Blueprint $table) {
            if (!$this->indexExists('attendances', 'att_user_date'))
                $table->index(['user_id', 'created_at'],   'att_user_date');
            if (!$this->indexExists('attendances', 'att_school_date'))
                $table->index(['school_id', 'created_at'], 'att_school_date');
            if (!$this->indexExists('attendances', 'att_user_school'))
                $table->index(['user_id', 'school_id'],    'att_user_school');
        });

        // ── USERS ────────────────────────────────────────────────────
        // Query: WHERE role=? AND status=? AND school_id=?
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'usr_role_status'))
                $table->index(['role', 'status'],            'usr_role_status');
            if (!$this->indexExists('users', 'usr_school_role'))
                $table->index(['school_id', 'role', 'status'], 'usr_school_role');
            if (!$this->indexExists('users', 'usr_ward_role'))
                $table->index(['ward_id', 'role'],           'usr_ward_role');
            if (!$this->indexExists('users', 'usr_council_role'))
                $table->index(['council_id', 'role'],        'usr_council_role');
        });

        // ── SCHOOLS ──────────────────────────────────────────────────
        // Query: WHERE ward_id=? AND is_active=?
        Schema::table('schools', function (Blueprint $table) {
            if (!$this->indexExists('schools', 'sch_ward_active'))
                $table->index(['ward_id', 'is_active'], 'sch_ward_active');
        });

        // ── WARDS ────────────────────────────────────────────────────
        Schema::table('wards', function (Blueprint $table) {
            if (!$this->indexExists('wards', 'ward_council'))
                $table->index(['council_id'], 'ward_council');
        });

        // ── TRANSFERS ────────────────────────────────────────────────
        // Query: WHERE status=? ORDER BY created_at
        Schema::table('transfers', function (Blueprint $table) {
            if (!$this->indexExists('transfers', 'tr_status_created'))
                $table->index(['status', 'created_at'], 'tr_status_created');
            if (!$this->indexExists('transfers', 'tr_user_status'))
                $table->index(['user_id', 'status'],    'tr_user_status');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', fn($t) => $t->dropIndexIfExists(['att_user_date','att_school_date','att_user_school']));
        Schema::table('users',       fn($t) => $t->dropIndexIfExists(['usr_role_status','usr_school_role','usr_ward_role','usr_council_role']));
        Schema::table('schools',     fn($t) => $t->dropIndexIfExists(['sch_ward_active']));
        Schema::table('wards',       fn($t) => $t->dropIndexIfExists(['ward_council']));
        Schema::table('transfers',   fn($t) => $t->dropIndexIfExists(['tr_status_created','tr_user_status']));
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(\DB::select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')->contains($index);
    }
};