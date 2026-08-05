<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class BarangHabisPakai extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'barang_habis_pakais';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok',
        'stok_minimal',
        'deskripsi',
    ];

    protected $casts = [
        'stok' => 'integer',
        'stok_minimal' => 'integer',
    ];

    /**
     * Hubungan ke tabel transaksi (Barang habis pakai memiliki banyak riwayat mutasi masuk/keluar)
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiBarangHabisPakai::class, 'barang_habis_pakai_id');
    }
}
