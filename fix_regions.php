<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Fix Provinsi
$updated = DB::table('siswa')
    ->whereRaw("provinsi !~ '^[0-9]+$'")
    ->update(['provinsi' => '32']);
echo "Siswa provinsi fixed: $updated\n";

// Fix Kota
$updated = DB::table('siswa')
    ->whereRaw("kota !~ '^[0-9]+$'")
    ->update(['kota' => '3216']);
echo "Siswa kota fixed: $updated\n";

// Fix Kecamatan
$updated = DB::table('siswa')
    ->whereRaw("kecamatan !~ '^[0-9]+$'")
    ->update(['kecamatan' => '3216060']);
echo "Siswa kecamatan fixed: $updated\n";

// Fix Kelurahan
$updated = DB::table('siswa')
    ->whereRaw("kelurahan_desa !~ '^[0-9]+$'")
    ->update(['kelurahan_desa' => '3216060001']);
echo "Siswa kelurahan fixed: $updated\n";

// Lakukan hal yang sama untuk wali_siswa
// Fix Provinsi
$updated = DB::table('wali_siswa')
    ->whereRaw("provinsi !~ '^[0-9]+$'")
    ->update(['provinsi' => '32']);
echo "Wali provinsi fixed: $updated\n";

// Fix Kota
$updated = DB::table('wali_siswa')
    ->whereRaw("kota !~ '^[0-9]+$'")
    ->update(['kota' => '3216']);
echo "Wali kota fixed: $updated\n";

// Fix Kecamatan
$updated = DB::table('wali_siswa')
    ->whereRaw("kecamatan !~ '^[0-9]+$'")
    ->update(['kecamatan' => '3216060']);
echo "Wali kecamatan fixed: $updated\n";

// Fix Kelurahan
$updated = DB::table('wali_siswa')
    ->whereRaw("kelurahan_desa !~ '^[0-9]+$'")
    ->update(['kelurahan_desa' => '3216060001']);
echo "Wali kelurahan fixed: $updated\n";

echo "Done.";
