<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;

class KartuSiswaController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        return view('kesiswaan.kartu_siswa.index', compact('kelas'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $siswa = Siswa::where('kelas_id', $kelas->id)
                      ->where('status_siswa', 'Aktif')
                      ->orderBy('nama_lengkap', 'asc')
                      ->get();

        return view('kesiswaan.kartu_siswa.cetak', compact('kelas', 'siswa'));
    }
}
