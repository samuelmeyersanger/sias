<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

\Illuminate\Support\Facades\DB::table('siswa')->where('provinsi', '32')->update([
    'provinsi' => 'Jawa Barat',
    'kota' => 'Kabupaten Bekasi',
    'kecamatan' => 'Tarumajaya',
    'kelurahan_desa' => 'Pantai Makmur'
]);

echo "DB Fixed!";
