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
            $isiSurat = '<p style="text-align: center;"><strong>📄 Dokumen Berkas SK / Surat Resmi Diunggah Langsung (File Terlampir)</strong></p>';
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

        $noUrutTerakhir = SuratKeluar::whereYear('tanggal_surat', $tahun)->whereNotNull('no_urut')->max('no_urut') ?? 0;
        $noUrutBaru = $noUrutTerakhir + 1;
        $strNoUrut = sprintf("%03d", $noUrutBaru);

        $nomorSuratFinal = str_replace(
            ['[kode]', '[nomor]', '[bulan]', '[tahun]', '[KODE]', '[NOMOR]', '[BULAN]', '[TAHUN]'],
            [$jenis->kode_klasifikasi, $strNoUrut, $bulanRomawi, $tahun, $jenis->kode_klasifikasi, $strNoUrut, $bulanRomawi, $tahun],
            $jenis->format_nomor
        );

        $surat->update([
            'no_urut'     => $noUrutBaru,
            'nomor_surat' => $nomorSuratFinal,
            'status'      => 'Disetujui'
        ]);

        return redirect()->back()->with('success', 'Surat disetujui! Nomor: ' . $nomorSuratFinal);
    }

    public function cetakPdf($id)
    {
        $surat = SuratKeluar::with(['jenisSurat', 'penandatangan', 'lampiran'])->findOrFail($id);
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