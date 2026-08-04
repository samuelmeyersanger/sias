<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class PerpustakaanBuku extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'perpustakaan_buku';

    protected $fillable = [
        'invoice_id',
        'judul_buku',
        'isbn',
        'penerbit',
        'tahun_terbit',
        'jumlah_eksemplar',
    ];

    public function invoice()
    {
        return $this->belongsTo(PerpustakaanInvoice::class, 'invoice_id');
    }
}
