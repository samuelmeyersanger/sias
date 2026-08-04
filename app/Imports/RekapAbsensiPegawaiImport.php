<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\RekapAbsensiPegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RekapAbsensiPegawaiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Headers akan dinormalisasi menjadi slug (e.g. tanggal_yyyy_mm_dd, nip_pegawai, jam_datang_hh_mm_kosongkan_jika_absen)
        // Kita bisa ambil index angka jika format header berubah, atau kita pakai nama kolom standar jika WithHeadingRow.
        // Asumsikan key yang digenerate adalah:
        // 'tanggal_yyyy_mm_dd', 'nip_pegawai', 'jam_datang_hh_mm_kosongkan_jika_absen', 'jam_pulang_hh_mm_kosongkan_jika_absen'
        
        $keys = array_keys($row);
        
        $tanggalRaw = $row[$keys[0]] ?? null;
        $nip = $row[$keys[1]] ?? null;
        $jamDatangRaw = $row[$keys[2]] ?? null;
        $jamPulangRaw = $row[$keys[3]] ?? null;

        if (!$tanggalRaw || !$nip) {
            return null; // Skip jika tidak ada tanggal atau NIP
        }

        // Cari pegawai berdasarkan NIP
        $pegawai = Pegawai::where('nip', $nip)->first();
        if (!$pegawai) {
            Log::warning('Import Absensi: Pegawai dengan NIP ' . $nip . ' tidak ditemukan.');
            return null; // Skip jika pegawai tidak ditemukan
        }

        // Handle format Excel Date (numeric)
        try {
            if (is_numeric($tanggalRaw)) {
                $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
            } else {
                $tanggal = Carbon::parse($tanggalRaw)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Log::error('Import Absensi: Format tanggal salah ' . $tanggalRaw);
            return null;
        }

        // Parse Time (Excel time is a fraction of a day)
        $jamDatang = null;
        if (!empty($jamDatangRaw)) {
            if (is_numeric($jamDatangRaw)) {
                $jamDatang = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($jamDatangRaw)->format('H:i');
            } else {
                $jamDatang = substr(str_replace('.', ':', $jamDatangRaw), 0, 5);
            }
        }

        $jamPulang = null;
        if (!empty($jamPulangRaw)) {
            if (is_numeric($jamPulangRaw)) {
                $jamPulang = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($jamPulangRaw)->format('H:i');
            } else {
                $jamPulang = substr(str_replace('.', ':', $jamPulangRaw), 0, 5);
            }
        }

        $jumlah_jam_kerja = $this->hitungJamKerja($jamDatang, $jamPulang);

        // Jika sudah ada data di tanggal yang sama untuk pegawai ini, update saja
        $existing = RekapAbsensiPegawai::where('pegawai_id', $pegawai->id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($existing) {
            $existing->update([
                'jam_datang' => $jamDatang,
                'jam_pulang' => $jamPulang,
                'jumlah_jam_kerja' => $jumlah_jam_kerja,
            ]);
            return null;
        }

        return new RekapAbsensiPegawai([
            'tanggal' => $tanggal,
            'pegawai_id' => $pegawai->id,
            'jam_datang' => $jamDatang,
            'jam_pulang' => $jamPulang,
            'jumlah_jam_kerja' => $jumlah_jam_kerja,
        ]);
    }

    private function hitungJamKerja($datang, $pulang)
    {
        if (empty($datang) || empty($pulang)) {
            return null;
        }

        try {
            $waktuDatang = Carbon::parse($datang);
            $waktuPulang = Carbon::parse($pulang);
            
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
}
