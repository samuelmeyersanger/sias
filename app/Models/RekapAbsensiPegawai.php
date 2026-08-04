<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapAbsensiPegawai extends Model
{
    use SoftDeletes;

    protected $table = 'rekap_absensi_pegawai';

    protected $fillable = [
        'tanggal',
        'pegawai_id',
        'jam_datang',
        'jam_pulang',
        'jumlah_jam_kerja',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke model Pegawai (Guru/Staff).
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
