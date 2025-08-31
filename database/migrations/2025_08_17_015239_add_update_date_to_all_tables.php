<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUpdateDateToAllTables extends Migration
{
    public function up(): void
    {
        // 1) 更新時に update_date を現在時刻にする関数（存在しなければ作成）
        DB::unprepared(<<<'SQL'
CREATE OR REPLACE FUNCTION set_update_date()
RETURNS trigger AS $$
BEGIN
  NEW.update_date := NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;
SQL);

        // 2) 対象テーブル（存在するものだけ処理）
        $tables = [
            'users','messages','cache','cache_locks','failed_jobs',
            'job_batches','jobs','migrations','password_reset_tokens','sessions','posts'
        ];

        foreach ($tables as $t) {
            if (!Schema::hasTable($t)) continue;

            // カラムが無ければ追加
            if (!Schema::hasColumn($t, 'update_date')) {
                DB::statement("ALTER TABLE {$t} ADD COLUMN update_date TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW();");
            }

            // 既存トリガーを外してから付け直し
            DB::statement("DROP TRIGGER IF EXISTS trg_{$t}_update_date ON {$t};");
            DB::statement("CREATE TRIGGER trg_{$t}_update_date BEFORE UPDATE ON {$t}
                           FOR EACH ROW EXECUTE FUNCTION set_update_date();");
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
            DB::statement("DROP TRIGGER IF EXISTS trg_{$t}_update_date ON {$t};");
            if (Schema::hasColumn($t, 'update_date')) {
                DB::statement("ALTER TABLE {$t} DROP COLUMN update_date;");
            }
        }

        // 関数は残しても害はありませんが、戻すなら解除
        // DB::statement("DROP FUNCTION IF EXISTS set_update_date();");
    }
}