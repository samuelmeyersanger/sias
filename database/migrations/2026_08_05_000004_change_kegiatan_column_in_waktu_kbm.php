<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, changing an enum is tricky because it uses CHECK constraints.
        // The safest and easiest way to allow new string values is to drop the enum CHECK constraint.
        // This makes the column behave like a normal VARCHAR column.
        
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE waktu_kbm DROP CONSTRAINT IF EXISTS waktu_kbm_kegiatan_check');
        } else {
            // For MySQL, we can change the column type to string
            Schema::table('waktu_kbm', function (Blueprint $table) {
                $table->string('kegiatan')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
