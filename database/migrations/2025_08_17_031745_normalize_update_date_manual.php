<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $tables = ['users', 'messages'];

    public function up(): void {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) === false) { continue; }
            DB::statement('DROP TRIGGER IF EXISTS trg_' . $t . '_update_date ON ' . $t . ';');
            DB::statement('ALTER TABLE ' . $t . ' ALTER COLUMN update_date DROP DEFAULT;');
            DB::statement('UPDATE ' . $t . ' SET update_date = NOW() WHERE update_date IS NULL;');
            DB::statement('ALTER TABLE ' . $t . ' ALTER COLUMN update_date SET NOT NULL;');
        }
        DB::statement('DROP FUNCTION IF EXISTS set_update_date();');
    }

    public function down(): void {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) === false) { continue; }
            DB::statement('ALTER TABLE ' . $t . ' ALTER COLUMN update_date DROP DEFAULT;');
        }
    }
};
