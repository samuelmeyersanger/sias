<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class TransaksiBarangHabisPakai extends Model
{
    use Loggable;

    protected $table = 'transaksi_barang_habis_pakais';

    protected $fillable = [
        'barang_habis_pakai_id',
        'jenis_transaksi',
        'jumlah',
        'tanggal',
        'pegawai_id',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Barang Habis Pakai
     */
    public function barangHabisPakai(): BelongsTo
    {
        return $this->belongsTo(BarangHabisPakai::class, 'barang_habis_pakai_id');
    }

    /**
     * Relasi ke Pegawai (Penerima/Peminjam)
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Relasi ke User (Admin yang mencatat)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
