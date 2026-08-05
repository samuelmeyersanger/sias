<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('menus')->insert([
            'kategori' => 'Sarana Prasarana',
            'nama_menu' => 'Barang Habis Pakai',
            'url' => 'sarpras/barang-habis-pakai',
            'icon' => 'fas fa-box-open',
            'urutan' => 18,
            'permission_slug' => 'sarpras.barang-habis-pakai.index',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menus')->where('url', 'sarpras/barang-habis-pakai')->delete();
    }
};
