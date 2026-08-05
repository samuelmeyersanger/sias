<?php

namespace App\Http\Controllers\Sarpras;

use App\Http\Controllers\Controller;
use App\Models\BarangHabisPakai;
use App\Models\TransaksiBarangHabisPakai;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangHabisPakaiController extends Controller
{
    /**
     * Menampilkan daftar Master Barang Habis Pakai
     */
    public function index()
    {
        $barangs = BarangHabisPakai::orderBy('nama_barang')->get();
        return view('sarpras.barang_habis_pakai.index', compact('barangs'));
    }

    /**
     * Menyimpan data master barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|string|unique:barang_habis_pakais,kode_barang',
            'nama_barang'  => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:255',
            'satuan'       => 'nullable|string|max:50',
            'stok_minimal' => 'nullable|integer|min:0',
            'deskripsi'    => 'nullable|string',
        ]);

        BarangHabisPakai::create([
            'kode_barang'  => $request->kode_barang,
            'nama_barang'  => $request->nama_barang,
            'kategori'     => $request->kategori,
            'satuan'       => $request->satuan,
            'stok'         => 0, // Stok awal selalu 0, ditambah via transaksi
            'stok_minimal' => $request->stok_minimal ?? 0,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('sarpras.barang-habis-pakai.index')
            ->with('success', 'Master Barang Habis Pakai berhasil ditambahkan.');
    }

    /**
     * Memperbarui data master barang
     */
    public function update(Request $request, $id)
    {
        $barang = BarangHabisPakai::findOrFail($id);

        $request->validate([
            'kode_barang'  => 'required|string|unique:barang_habis_pakais,kode_barang,' . $id,
            'nama_barang'  => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:255',
            'satuan'       => 'nullable|string|max:50',
            'stok_minimal' => 'nullable|integer|min:0',
            'deskripsi'    => 'nullable|string',
        ]);

        $barang->update([
            'kode_barang'  => $request->kode_barang,
            'nama_barang'  => $request->nama_barang,
            'kategori'     => $request->kategori,
            'satuan'       => $request->satuan,
            'stok_minimal' => $request->stok_minimal ?? 0,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('sarpras.barang-habis-pakai.index')
            ->with('success', 'Master Barang Habis Pakai berhasil diperbarui.');
    }

    /**
     * Menghapus master barang (soft delete)
     */
    public function destroy($id)
    {
        $barang = BarangHabisPakai::findOrFail($id);
        $barang->delete();

        return redirect()->route('sarpras.barang-habis-pakai.index')
            ->with('success', 'Master Barang Habis Pakai berhasil dihapus.');
    }

    /**
     * Menampilkan detail barang beserta riwayat transaksinya
     */
    public function show($id)
    {
        $barang = BarangHabisPakai::with(['transaksi.pegawai', 'transaksi.user'])
            ->findOrFail($id);
            
        // Urutkan transaksi dari yang terbaru
        $transaksis = $barang->transaksi()->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();
        
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();

        return view('sarpras.barang_habis_pakai.show', compact('barang', 'transaksis', 'pegawais'));
    }

    /**
     * Mencatat transaksi (Masuk / Keluar) dan mengupdate stok
     */
    public function storeTransaksi(Request $request, $barang_id)
    {
        $barang = BarangHabisPakai::findOrFail($barang_id);

        $request->validate([
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'jumlah'          => 'required|integer|min:1',
            'tanggal'         => 'required|date',
            'pegawai_id'      => 'nullable|exists:pegawai,id',
            'keterangan'      => 'nullable|string',
        ]);

        // Cek stok khusus untuk barang keluar
        if ($request->jenis_transaksi === 'keluar' && $request->jumlah > $barang->stok) {
            return back()->with('error', 'Gagal: Jumlah pengeluaran melebihi stok yang tersedia (' . $barang->stok . ' ' . $barang->satuan . ').');
        }

        DB::beginTransaction();
        try {
            // Buat transaksi
            TransaksiBarangHabisPakai::create([
                'barang_habis_pakai_id' => $barang->id,
                'jenis_transaksi'       => $request->jenis_transaksi,
                'jumlah'                => $request->jumlah,
                'tanggal'               => $request->tanggal,
                'pegawai_id'            => $request->jenis_transaksi === 'keluar' ? $request->pegawai_id : null,
                'keterangan'            => $request->keterangan,
                'user_id'               => auth()->id(),
            ]);

            // Update stok di master barang
            if ($request->jenis_transaksi === 'masuk') {
                $barang->increment('stok', $request->jumlah);
            } else {
                $barang->decrement('stok', $request->jumlah);
            }

            DB::commit();
            return redirect()->route('sarpras.barang-habis-pakai.show', $barang->id)
                ->with('success', 'Transaksi Barang ' . ucfirst($request->jenis_transaksi) . ' berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }
}
