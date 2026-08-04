<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class PerpustakaanInvoice extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'perpustakaan_invoice';

    protected $fillable = [
        'nomor_invoice',
        'tanggal_invoice',
        'nama_suplier',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
    ];

    public function buku()
    {
        return $this->hasMany(PerpustakaanBuku::class, 'invoice_id');
    }
}
