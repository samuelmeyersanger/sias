<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\KodeGuru;
use App\Models\JadwalPelajaran; // Sesuaikan jika nama model jadwal Anda berbeda
use App\Models\WaktuKbm;

class JadwalMengajarController extends Controller
{
    /**
     * Menampilkan Jadwal Mengajar Pribadi Guru (Read-Only)
     */
    public function index(Request $request)
    {
        // 1. Ambil data pegawai dari user yang sedang login
        $pegawai = Pegawai::where('user_id', auth()->id())->first();
        $kelasWali = null;

        $jadwalPelajaran = collect();
        $daftarWaktu = collect();

        if ($pegawai) {
            // Ambil data kelas jika dia adalah wali kelas
            $kelasWali = \App\Models\Kelas::where('wali_kelas_id', $pegawai->id)->first();
            // 2. Ambil semua ID Kode Guru milik pegawai ini
            $kodeGuruIds = KodeGuru::where('pegawai_id', $pegawai->id)->pluck('id');

            if ($kodeGuruIds->isNotEmpty()) {
                // 3. Tarik jadwal HANYA yang kode_guru_id-nya milik guru ini dan urutkan
                $jadwalPelajaran = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'ruangan'])
                                    ->leftJoin('waktu_kbm', 'jadwal_pelajaran.waktu_kbm_id', '=', 'waktu_kbm.id')
                                    ->select('jadwal_pelajaran.*')
                                    ->whereIn('jadwal_pelajaran.kode_guru_id', $kodeGuruIds)
                                    ->orderByRaw("
                                        CASE jadwal_pelajaran.hari 
                                            WHEN 'Senin' THEN 1
                                            WHEN 'Selasa' THEN 2
                                            WHEN 'Rabu' THEN 3
                                            WHEN 'Kamis' THEN 4
                                            WHEN 'Jumat' THEN 5
                                            WHEN 'Sabtu' THEN 6
                                            ELSE 7 
                                        END
                                    ")
                                    ->orderBy('waktu_kbm.waktu_mulai', 'asc')
                                    ->orderBy('waktu_kbm.waktu_selesai', 'asc')
                                    ->get();

                // 4. Tarik master waktu untuk grid tabel
                $daftarWaktu = WaktuKbm::orderByRaw("
                                    CASE hari 
                                        WHEN 'Senin' THEN 1
                                        WHEN 'Selasa' THEN 2
                                        WHEN 'Rabu' THEN 3
                                        WHEN 'Kamis' THEN 4
                                        WHEN 'Jumat' THEN 5
                                        WHEN 'Sabtu' THEN 6
                                        ELSE 7 
                                    END
                                ")
                                ->orderBy('jam_ke', 'asc')
                                ->get();
            }
        }

        // Return view diarahkan ke folder akademik
        return view('akademik.jadwal_mengajar.index', compact('pegawai', 'jadwalPelajaran', 'daftarWaktu', 'kelasWali'));
    }

        /**
     * Fitur Download / Cetak PDF Jadwal Mengajar Pribadi
     */
    public function downloadPdf(Request $request)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->first();
        $kelasWali = null;
        $jadwalPelajaran = collect();
        $daftarWaktu = collect();
        if ($pegawai) {
            $kelasWali = \App\Models\Kelas::where('wali_kelas_id', $pegawai->id)->first();
            $kodeGuruIds = KodeGuru::where('pegawai_id', $pegawai->id)->pluck('id');
            if ($kodeGuruIds->isNotEmpty()) {
                // Relasi disesuaikan dengan kode index() milik Anda
                $jadwalPelajaran = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'ruangan'])
                                    ->leftJoin('waktu_kbm', 'jadwal_pelajaran.waktu_kbm_id', '=', 'waktu_kbm.id')
                                    ->select('jadwal_pelajaran.*')
                                    ->whereIn('jadwal_pelajaran.kode_guru_id', $kodeGuruIds)
                                    ->orderByRaw("
                                        CASE jadwal_pelajaran.hari 
                                            WHEN 'Senin' THEN 1
                                            WHEN 'Selasa' THEN 2
                                            WHEN 'Rabu' THEN 3
                                            WHEN 'Kamis' THEN 4
                                            WHEN 'Jumat' THEN 5
                                            WHEN 'Sabtu' THEN 6
                                            ELSE 7 
                                        END
                                    ")
                                    ->orderBy('waktu_kbm.waktu_mulai', 'asc')
                                    ->orderBy('waktu_kbm.waktu_selesai', 'asc')
                                    ->get();
                $daftarWaktu = WaktuKbm::orderByRaw("
                                    CASE hari 
                                        WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2
                                        WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4
                                        WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6
                                        ELSE 7 
                                    END
                                ")->orderBy('jam_ke', 'asc')->get();
            }
        }
        // Arahkan ke file cetak
        return view('akademik.jadwal_mengajar.cetak', compact('pegawai', 'jadwalPelajaran', 'daftarWaktu', 'kelasWali'));
    }
}