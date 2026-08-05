<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah Permission
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'sarpras.barang-habis-pakai.index',
            'display_name' => 'sarpras.barang-habis-pakai.index',
            'description' => 'Mengelola inventaris barang habis pakai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Berikan akses ke Admin
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        if ($adminRole) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRole->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->where('name', 'sarpras.barang-habis-pakai.index')->delete();
    }
};
