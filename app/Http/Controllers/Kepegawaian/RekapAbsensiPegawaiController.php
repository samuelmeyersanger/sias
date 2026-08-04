<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\RekapAbsensiPegawai;
use Carbon\Carbon;

class RekapAbsensiPegawaiController extends Controller
{
    /**
     * Tampilkan halaman rekap absensi.
     */
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap', 'asc')->get();
        $absensis = RekapAbsensiPegawai::with('pegawai')->orderBy('tanggal', 'desc')->get();
        return view('kepegawaian.rekap_absensi_pegawai.index', compact('pegawais', 'absensis'));
    }

    /**
     * Hitung selisih jam kerja dalam format string yang rapi.
     */
    private function hitungJamKerja($datang, $pulang)
    {
        if (empty($datang) || empty($pulang)) {
            return null;
        }

        try {
            $waktuDatang = Carbon::parse($datang);
            $waktuPulang = Carbon::parse($pulang);
            
            // Jika pulang keesokan harinya
            if ($waktuPulang->lt($waktuDatang)) {
                $waktuPulang->addDay();
            }

            $diffInMinutes = $waktuDatang->diffInMinutes($waktuPulang);
            
            $jam = floor($diffInMinutes / 60);
            $menit = $diffInMinutes % 60;
            
            $result = [];
            if ($jam > 0) $result[] = $jam . " Jam";
            if ($menit > 0) $result[] = $menit . " Menit";
            
            return empty($result) ? "0 Menit" : implode(" ", $result);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Simpan data absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pegawai_id' => 'required|exists:pegawai,id',
            'jam_datang' => 'nullable',
            'jam_pulang' => 'nullable',
        ]);

        $jumlah_jam_kerja = $this->hitungJamKerja($request->jam_datang, $request->jam_pulang);

        RekapAbsensiPegawai::create([
            'tanggal' => $request->tanggal,
            'pegawai_id' => $request->pegawai_id,
            'jam_datang' => $request->jam_datang,
            'jam_pulang' => $request->jam_pulang,
            'jumlah_jam_kerja' => $jumlah_jam_kerja,
        ]);

        return redirect()->route('kepegawaian.rekap-absensi.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Update data absensi.
     */
    public function update(Request $request, $id)
    {
        $absensi = RekapAbsensiPegawai::findOrFail($id);
        
        $request->validate([
            'tanggal' => 'required|date',
            'pegawai_id' => 'required|exists:pegawai,id',
            'jam_datang' => 'nullable',
            'jam_pulang' => 'nullable',
        ]);

        $jumlah_jam_kerja = $this->hitungJamKerja($request->jam_datang, $request->jam_pulang);

        $absensi->update([
            'tanggal' => $request->tanggal,
            'pegawai_id' => $request->pegawai_id,
            'jam_datang' => $request->jam_datang,
            'jam_pulang' => $request->jam_pulang,
            'jumlah_jam_kerja' => $jumlah_jam_kerja,
        ]);

        return redirect()->route('kepegawaian.rekap-absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Hapus data absensi.
     */
    public function destroy($id)
    {
        $absensi = RekapAbsensiPegawai::findOrFail($id);
        $absensi->delete();

        return redirect()->route('kepegawaian.rekap-absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
