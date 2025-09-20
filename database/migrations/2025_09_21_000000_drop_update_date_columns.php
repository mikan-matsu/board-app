<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $tables = [
        'users',
        'messages',
        'cache',
        'cache_locks',
        'failed_jobs',
        'job_batches',
        'jobs',
        'migrations',
        'password_reset_tokens',
        'sessions',
        'posts',
    ];

    public function up(): void {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'update_date')) {
                Schema::table($t, function ($table) use ($t) {
                    $table->dropColumn('update_date');
                });
            }
        }
    }

    public function down(): void {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && !Schema::hasColumn($t, 'update_date')) {
                Schema::table($t, function ($table) use ($t) {
                    $table->timestamp('update_date')->nullable();
                });
            }
        }
    }
};
