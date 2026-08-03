<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use App\Models\SuratKeluarLampiran;
use App\Models\PengaturanLogo;
use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat', 'penandatangan'])->latest()->get();
        $jenisSurat = JenisSurat::all();
        $daftarKepsek = User::all(); 
        $daftarPegawai = \App\Models\Pegawai::where('status_keaktifan', 'Aktif')->orderBy('nama_lengkap', 'asc')->get();
        $daftarSiswa = \App\Models\Siswa::where('status_siswa', 'Aktif')->with(['kelas', 'wali'])->orderBy('nama_lengkap', 'asc')->get();

        return view('surat.surat_keluar.index', compact('suratKeluar', 'jenisSurat', 'daftarKepsek', 'daftarPegawai', 'daftarSiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id'    => 'required|exists:jenis_surat,id',
            'perihal'           => 'required|string',
            'tujuan_surat'      => 'required|string',
            'tanggal_surat'     => 'required|date',
            'metode_ttd'        => 'required|in:Digital,Basah',
            'penandatangan_id'  => 'required|exists:users,id',
            'file_dokumen'      => 'nullable|mimes:pdf,doc,docx,xlsx,xls|max:10240',
            'file_excel'        => 'nullable|mimes:xlsx,xls'
        ], [
            'jenis_surat_id.required'   => 'Klasifikasi Format Surat wajib dipilih.',
            'perihal.required'          => 'Perihal Surat wajib diisi.',
            'tujuan_surat.required'     => 'Tujuan Surat / Kepada Yth. wajib diisi.',
            'tanggal_surat.required'    => 'Tanggal Surat wajib dipilih.',
            'penandatangan_id.required' => 'Penandatangan Kepala Sekolah wajib dipilih.',
            'file_dokumen.mimes'        => 'File dokumen harus berformat PDF, Word (.doc/.docx), atau Excel.',
            'file_dokumen.max'          => 'Ukuran file dokumen maksimal 10 MB.'
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('surat_keluar_files', 'public');
        }

        $isiSurat = $request->isi_surat;
        if ((!$isiSurat || $isiSurat === '<p><br></p>') && $filePath) {
            $isiSurat = '<p style="text-align: center;"><strong>Dokumen Berkas SK / Surat Resmi Diunggah Langsung (File Terlampir)</strong></p>';
        }

        $surat = SuratKeluar::create([
            'jenis_surat_id'   => $request->jenis_surat_id,
            'tujuan_surat'     => $request->tujuan_surat,
            'perihal'          => $request->perihal,
            'isi_surat'        => $isiSurat ?? '-',
            'tanggal_surat'    => $request->tanggal_surat,
            'metode_ttd'       => $request->metode_ttd,
            'penandatangan_id' => $request->penandatangan_id,
            'file_final'       => $filePath,
            'status'           => 'Menunggu Persetujuan',
            'pembuat_id'       => auth()->id(),
        ]);

        // Jika user mengunggah file Excel lampiran
        if ($request->hasFile('file_excel')) {
            $this->prosesImportExcel($request->file('file_excel'), $surat->id);
        }

        return redirect()->back()->with('success', 'Usulan draf surat / dokumen berhasil diajukan!');
    }

    /**
     * Helper Fungsi Importer Excel "Buta" (Universal)
     */
    private function prosesImportExcel($file, $suratId)
    {
        $rows = Excel::toArray([], $file)[0];
        
        // 1. Ambil baris pertama sebagai nama Header Judul Kolom
        $header = $rows[0];
        $surat = SuratKeluar::find($suratId);
        $surat->update([
            'header_1' => $header[0] ?? null,
            'header_2' => $header[1] ?? null,
            'header_3' => $header[2] ?? null,
            'header_4' => $header[3] ?? null,
            'header_5' => $header[4] ?? null,
        ]);

        // 2. Buang baris pertama agar tidak masuk ke baris data orang
        unset($rows[0]);

        // Hapus lampiran lama jika ada (untuk keperluan re-upload)
        SuratKeluarLampiran::where('surat_keluar_id', $suratId)->delete();

        // 3. Simpan sisa baris secara anonim berurutan
        foreach ($rows as $row) {
            SuratKeluarLampiran::create([
                'surat_keluar_id' => $suratId,
                'kolom_1'         => $row[0] ?? null,
                'kolom_2'         => $row[1] ?? null,
                'kolom_3'         => $row[2] ?? null,
                'kolom_4'         => $row[3] ?? null,
                'kolom_5'         => $row[4] ?? null,
            ]);
        }
    }

    public function setujui($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $jenis = JenisSurat::findOrFail($surat->jenis_surat_id);

        $tahun = Carbon::parse($surat->tanggal_surat)->year;
        $bulanRomawi = $this->getRomawi(Carbon::parse($surat->tanggal_surat)->format('m'));
        $tglIndo = Carbon::parse($surat->tanggal_surat)->locale('id')->isoFormat('D MMMM YYYY');

        $noUrutTerakhir = SuratKeluar::whereYear('tanggal_surat', $tahun)->whereNotNull('no_urut')->max('no_urut') ?? 0;
        $noUrutBaru = $noUrutTerakhir + 1;
        $strNoUrut = sprintf("%03d", $noUrutBaru);

        $nomorSuratFinal = str_replace(
            ['[kode]', '[nomor]', '[bulan]', '[tahun]', '[KODE]', '[NOMOR]', '[BULAN]', '[TAHUN]'],
            [$jenis->kode_klasifikasi, $strNoUrut, $bulanRomawi, $tahun, $jenis->kode_klasifikasi, $strNoUrut, $bulanRomawi, $tahun],
            $jenis->format_nomor
        );

        $namaKepsek = $surat->penandatangan ? $surat->penandatangan->name : 'Siti Nurchayati, M.Pd';
        $nipKepsek = '197307152000032007';
        $pangkatKepsek = 'Pembina Utama Muda, IV/c';
        $blokTtd = $namaKepsek . "\n" . $pangkatKepsek . "\nNIP. " . $nipKepsek;

        $replacements = [
            '[kode]'                   => $jenis->kode_klasifikasi,
            '[nomor]'                  => $strNoUrut,
            '[bulan]'                  => $bulanRomawi,
            '[tahun]'                  => $tahun,
            '[KODE]'                   => $jenis->kode_klasifikasi,
            '[NOMOR]'                  => $strNoUrut,
            '[BULAN]'                  => $bulanRomawi,
            '[TAHUN]'                  => $tahun,
            '[tanggal_surat]'          => $tglIndo,
            '[tanggal]'                => $tglIndo,
            '[penandatangan_id]'       => $blokTtd,
            '[penandatangan]'          => $blokTtd,
            '[nama_kepsek]'            => $namaKepsek,
            '[nip_kepsek]'             => $nipKepsek,
            '[pangkat_kepsek]'          => $pangkatKepsek,
            '[pangkat_golongan_kepsek]' => $pangkatKepsek,
            '[golongan_kepsek]'        => $pangkatKepsek,
        ];

        // Jika surat berbasis file Word (.docx), ganti tag di dalam file Word
        if ($surat->file_final && str_contains(strtolower($surat->file_final), '.docx')) {
            $this->prosesTemplateDocx($surat->file_final, $replacements);
        }

        // Ganti tag di isi_surat jika mengetik lewat editor
        $newIsiSurat = $surat->isi_surat;
        foreach ($replacements as $search => $replace) {
            $newIsiSurat = str_replace($search, nl2br($replace), $newIsiSurat);
        }

        $surat->update([
            'no_urut'     => $noUrutBaru,
            'nomor_surat' => $nomorSuratFinal,
            'isi_surat'   => $newIsiSurat,
            'status'      => 'Disetujui'
        ]);

        return redirect()->back()->with('success', 'Surat disetujui! Nomor: ' . $nomorSuratFinal);
    }

    /**
     * Engine Pengganti Tag Otomatis untuk File Word (.docx)
     */
    private function prosesTemplateDocx($filePath, $replacements)
    {
        $fullPath = storage_path('app/public/' . $filePath);
        if (!file_exists($fullPath)) return;

        $zip = new \ZipArchive;
        if ($zip->open($fullPath) === TRUE) {
            $xmlContent = $zip->getFromName('word/document.xml');
            if ($xmlContent) {
                // 1. Bersihkan tag pemeriksa ejaan (spellcheck/proofErr) bawaan MS Word yang memecah kata
                $xmlContent = preg_replace('/<w:proofErr[^>]*\/>/', '', $xmlContent);
                $xmlContent = preg_replace('/<w:noProof[^>]*\/>/', '', $xmlContent);
                $xmlContent = preg_replace('/<w:lang[^>]*\/>/', '', $xmlContent);

                // 2. Gabungkan tag XML yang terpisah di dalam kurung siku [...]
                $xmlContent = preg_replace_callback('/\[(.*?)\]/s', function($matches) {
                    return '[' . strip_tags($matches[1]) . ']';
                }, $xmlContent);

                // 3. Lakukan penggantian tag secara presisi & fleksibel (Case-Insensitive Regex Fallback)
                foreach ($replacements as $search => $replace) {
                    $replaceXml = str_replace("\n", '</w:t><w:br/><w:t>', htmlspecialchars($replace, ENT_QUOTES, 'UTF-8'));
                    
                    // Ganti string langsung
                    $xmlContent = str_replace($search, $replaceXml, $xmlContent);

                    // Regex fallback untuk mengantisipasi variasi spasi / huruf kapital bawaan Word
                    $cleanSearch = trim($search, '[]');
                    $xmlContent = preg_replace(
                        '/\[\s*' . preg_quote($cleanSearch, '/') . '\s*\]/i',
                        $replaceXml,
                        $xmlContent
                    );
                }

                $zip->addFromString('word/document.xml', $xmlContent);
            }
            $zip->close();
        }
    }

    public function cetakPdf($id)
    {
        $surat = SuratKeluar::with(['jenisSurat', 'penandatangan', 'lampiran'])->findOrFail($id);

        $jenis = $surat->jenisSurat;
        $tahun = Carbon::parse($surat->tanggal_surat)->year;
        $bulanRomawi = $this->getRomawi(Carbon::parse($surat->tanggal_surat)->format('m'));
        $tglIndo = Carbon::parse($surat->tanggal_surat)->locale('id')->isoFormat('D MMMM YYYY');
        $strNoUrut = sprintf("%03d", $surat->no_urut ?? 1);
        $namaKepsek = $surat->penandatangan ? $surat->penandatangan->name : 'Siti Nurchayati, M.Pd';
        $nipKepsek = '197307152000032007';
        $pangkatKepsek = 'Pembina Utama Muda, IV/c';
        $blokTtd = $namaKepsek . "\n" . $pangkatKepsek . "\nNIP. " . $nipKepsek;

        $replacements = [
            '[kode]'                   => $jenis->kode_klasifikasi ?? '400.3.5.6',
            '[nomor]'                  => $strNoUrut,
            '[bulan]'                  => $bulanRomawi,
            '[tahun]'                  => $tahun,
            '[KODE]'                   => $jenis->kode_klasifikasi ?? '400.3.5.6',
            '[NOMOR]'                  => $strNoUrut,
            '[BULAN]'                  => $bulanRomawi,
            '[TAHUN]'                  => $tahun,
            '[tanggal_surat]'          => $tglIndo,
            '[tanggal]'                => $tglIndo,
            '[penandatangan_id]'       => $blokTtd,
            '[penandatangan]'          => $blokTtd,
            '[nama_kepsek]'            => $namaKepsek,
            '[nip_kepsek]'             => $nipKepsek,
            '[pangkat_kepsek]'          => $pangkatKepsek,
            '[pangkat_golongan_kepsek]' => $pangkatKepsek,
            '[golongan_kepsek]'        => $pangkatKepsek,
        ];

        // Jika surat berasal dari file unggahan (Word / PDF / Excel), langsung buka / unduh berkas aslinya
        if ($surat->file_final && \Illuminate\Support\Facades\Storage::disk('public')->exists($surat->file_final)) {
            if (str_contains(strtolower($surat->file_final), '.docx')) {
                $this->prosesTemplateDocx($surat->file_final, $replacements);
            }

            $path = storage_path('app/public/' . $surat->file_final);
            $mime = \Illuminate\Support\Facades\File::mimeType($path);
            if (str_contains($mime, 'pdf')) {
                return response()->file($path);
            } else {
                return response()->download($path);
            }
        }

        $pengaturan = PengaturanLogo::first();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('surat.surat_keluar.template_pdf', compact('surat', 'pengaturan'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('surat_' . ($surat->no_urut ?? 'draf') . '.pdf');
    }

    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $request->validate([
            'jenis_surat_id'    => 'required|exists:jenis_surat,id',
            'tujuan_surat'      => 'required|string',
            'perihal'           => 'required|string',
            'isi_surat'         => 'required|string',
            'tanggal_surat'     => 'required|date',
            'metode_ttd'        => 'required|in:Digital,Basah',
            'penandatangan_id'  => 'required|exists:users,id',
            'file_excel'        => 'nullable|mimes:xlsx,xls'
        ]);

        $surat->update([
            'jenis_surat_id'   => $request->jenis_surat_id,
            'tujuan_surat'     => $request->tujuan_surat,
            'perihal'          => $request->perihal,
            'isi_surat'        => $request->isi_surat,
            'tanggal_surat'    => $request->tanggal_surat,
            'metode_ttd'       => $request->metode_ttd,
            'penandatangan_id' => $request->penandatangan_id,
        ]);

        if ($request->hasFile('file_excel')) {
            $this->prosesImportExcel($request->file('file_excel'), $surat->id);
        }

        return redirect()->back()->with('success', 'Draf surat keluar berhasil diperbarui!');
    }

    public function tolak($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $surat->update(['status' => 'Ditolak']);

        return redirect()->back()->with('success', 'Draf surat telah ditolak.');
    }

    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        SuratKeluarLampiran::where('surat_keluar_id', $id)->delete();
        $surat->delete();

        return redirect()->back()->with('success', 'Draf surat keluar berhasil dihapus.');
    }

    private function getRomawi($bulan) {
        $map = ['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];
        return $map[$bulan] ?? 'I';
    }
}