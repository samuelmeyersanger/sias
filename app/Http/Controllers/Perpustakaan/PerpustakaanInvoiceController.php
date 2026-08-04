<?php

namespace App\Http\Controllers\Perpustakaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerpustakaanInvoice;
use App\Models\PerpustakaanBuku;
use Illuminate\Support\Str;

class PerpustakaanInvoiceController extends Controller
{
    /**
     * Tampilkan daftar invoice.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $invoices = PerpustakaanInvoice::withCount('buku')
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_invoice', 'like', "%{$search}%")
                      ->orWhere('nama_suplier', 'like', "%{$search}%");
            })
            ->latest() // Mengurutkan berdasarkan tanggal dibuat (terbaru di atas)
            ->paginate(15);

        return view('perpustakaan.invoice.index', compact('invoices', 'search'));
    }

    /**
     * Simpan invoice baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_invoice' => [
                'required',
                \Illuminate\Validation\Rule::unique('perpustakaan_invoice', 'nomor_invoice')->whereNull('deleted_at')
            ],
            'tanggal_invoice' => 'required|date',
            'nama_suplier' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_invoice.unique' => 'Nomor invoice ini sudah pernah digunakan. Harap masukkan nomor yang berbeda.',
            'nomor_invoice.required' => 'Nomor invoice wajib diisi.',
            'tanggal_invoice.required' => 'Tanggal invoice wajib diisi.',
            'nama_suplier.required' => 'Nama suplier wajib diisi.'
        ]);

        PerpustakaanInvoice::create($request->all());

        return redirect()->route('perpustakaan.invoice.index')->with('success', 'Data invoice berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail invoice beserta daftar buku.
     */
    public function show($id)
    {
        $invoice = PerpustakaanInvoice::with('buku')->findOrFail($id);
        return view('perpustakaan.invoice.show', compact('invoice'));
    }

    /**
     * Update data invoice.
     */
    public function update(Request $request, $id)
    {
        $invoice = PerpustakaanInvoice::findOrFail($id);
        
        $request->validate([
            'nomor_invoice' => [
                'required',
                \Illuminate\Validation\Rule::unique('perpustakaan_invoice', 'nomor_invoice')->ignore($id)->whereNull('deleted_at')
            ],
            'tanggal_invoice' => 'required|date',
            'nama_suplier' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_invoice.unique' => 'Nomor invoice ini sudah pernah digunakan. Harap masukkan nomor yang berbeda.',
            'nomor_invoice.required' => 'Nomor invoice wajib diisi.',
            'tanggal_invoice.required' => 'Tanggal invoice wajib diisi.',
            'nama_suplier.required' => 'Nama suplier wajib diisi.'
        ]);

        $invoice->update($request->all());

        return redirect()->route('perpustakaan.invoice.index')->with('success', 'Data invoice berhasil diubah.');
    }

    /**
     * Hapus data invoice beserta seluruh bukunya.
     */
    public function destroy($id)
    {
        $invoice = PerpustakaanInvoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('perpustakaan.invoice.index')->with('success', 'Data invoice berhasil dihapus.');
    }

    /**
     * Simpan buku baru ke dalam invoice.
     */
    public function storeBuku(Request $request, $invoice_id)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|string|max:4',
            'jumlah_eksemplar' => 'required|integer|min:1',
        ]);

        $invoice = PerpustakaanInvoice::findOrFail($invoice_id);

        $invoice->buku()->create($request->all());

        return redirect()->route('perpustakaan.invoice.show', $invoice_id)->with('success', 'Buku berhasil ditambahkan ke dalam invoice.');
    }

    /**
     * Hapus buku dari invoice.
     */
    public function destroyBuku($invoice_id, $buku_id)
    {
        $buku = PerpustakaanBuku::where('invoice_id', $invoice_id)->findOrFail($buku_id);
        $buku->delete();

        return redirect()->route('perpustakaan.invoice.show', $invoice_id)->with('success', 'Buku berhasil dihapus dari invoice.');
    }
}
