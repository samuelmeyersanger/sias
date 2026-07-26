<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\AbsensiHarian;
use Carbon\Carbon;

class AbsensiQrController extends Controller
{
    public function scanner()
    {
        return view('absensi.scanner');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'nisn' => 'required'
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan dengan NISN tersebut.'
            ], 404);
        }

        $hariIni = Carbon::today()->toDateString();
        $sekarang = Carbon::now();
        $waktu = $sekarang->toTimeString();

        // Cari absensi hari ini
        $absensi = AbsensiHarian::where('siswa_id', $siswa->id)
            ->where('tanggal', $hariIni)
            ->first();

        if (!$absensi) {
            // Belum absen masuk
            $statusHadir = 'Hadir';
            // Cek keterlambatan misal jam masuk 07:15
            $batasMasuk = Carbon::createFromTime(7, 15, 0);
            if ($sekarang->gt($batasMasuk)) {
                $statusHadir = 'Terlambat';
            }

            AbsensiHarian::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $hariIni,
                'waktu_masuk' => $waktu,
                'status' => $statusHadir
            ]);

            return response()->json([
                'status' => 'success',
                'type' => 'masuk',
                'message' => 'Berhasil absen MASUK',
                'siswa' => [
                    'nama' => $siswa->nama_lengkap,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
                    'waktu' => $waktu,
                    'status' => $statusHadir
                ]
            ]);
        } else {
            // Sudah absen masuk, cek apakah bisa absen pulang
            // Hindari scan berkali-kali dalam waktu dekat (misal jeda min 3 jam untuk pulang)
            $waktuMasuk = Carbon::parse($absensi->waktu_masuk);
            if ($sekarang->diffInHours($waktuMasuk) < 3) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Anda sudah absen masuk hari ini. Belum waktunya pulang.'
                ]);
            }

            if ($absensi->waktu_pulang) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Anda sudah melakukan absen pulang hari ini.'
                ]);
            }

            $absensi->update([
                'waktu_pulang' => $waktu
            ]);

            return response()->json([
                'status' => 'success',
                'type' => 'pulang',
                'message' => 'Berhasil absen PULANG',
                'siswa' => [
                    'nama' => $siswa->nama_lengkap,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
                    'waktu' => $waktu,
                    'status' => 'Pulang'
                ]
            ]);
        }
    }
}
