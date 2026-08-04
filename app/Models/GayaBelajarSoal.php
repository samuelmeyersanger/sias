<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class GayaBelajarSoal extends Model
{
    use HasFactory, Loggable;

    protected $table = 'gaya_belajar_soal';

    protected $fillable = [
        'pertanyaan',
        'opsi_visual',
        'opsi_auditory',
        'opsi_kinesthetic',
    ];
}