<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUpdateDateToAllTables extends Migration
{
    public function up(): void
{
    $tables = [
        'users','messages','cache','cache_locks','failed_jobs',
        'job_batches','jobs','migrations','password_reset_tokens','sessions','posts'
    ];

    foreach ($tables as $t) {
        if (!Schema::hasTable($t)) continue;

        if (!Schema::hasColumn($t, 'update_date')) {
            DB::statement("ALTER TABLE {$t} ADD COLUMN update_date TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW();");
        }
    }
}

public function down(): void
{
    $tables = [
        'users','messages','cache','cache_locks','failed_jobs',
        'job_batches','jobs','migrations','password_reset_tokens','sessions','posts'
    ];

    foreach ($tables as $t) {
        if (!Schema::hasTable($t)) continue;
        if (Schema::hasColumn($t, 'update_date')) {
            DB::statement("ALTER TABLE {$t} DROP COLUMN update_date;");
        }
    }
}
}