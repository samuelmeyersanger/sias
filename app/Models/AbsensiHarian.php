<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class AbsensiHarian extends Model
{
    use HasFactory, Loggable;

    protected $table = 'absensi_harian';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
