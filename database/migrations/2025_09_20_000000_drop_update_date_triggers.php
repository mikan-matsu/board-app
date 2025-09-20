<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $tables = [
        'users','messages','cache','cache_locks','failed_jobs',
        'job_batches','jobs','migrations','password_reset_tokens','sessions','posts'
    ];

    public function up(): void {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) === false) continue;
            DB::statement("DROP TRIGGER IF EXISTS trg_{$t}_update_date ON {$t};");
        }
        DB::statement("DROP FUNCTION IF EXISTS set_update_date();");
    }

    public function down(): void {
        // 何もしない（関数・トリガーは復元しない）
    }
};
