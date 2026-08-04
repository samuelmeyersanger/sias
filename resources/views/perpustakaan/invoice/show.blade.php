<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('perpustakaan.invoice.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 shadow-sm transition-colors" title="Kembali">
                <span class="text-xl font-bold">←</span>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="text-3xl">📖</span> {{ __('Rincian Buku: ' . $invoice->nomor_invoice) }}
            </h2>
        </div>
    </x-slot>

    <div x-data="{ openCreateBuku: false }" class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Informasi Invoice --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full relative z-10">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Penerimaan</p>
                        <p class="text-lg font-black text-gray-800 mt-1">{{ $invoice->tanggal_invoice->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Suplier / Toko</p>
                        <p class="text-lg font-black text-indigo-700 mt-1">🏢 {{ $invoice->nama_suplier }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</p>
                        <p class="text-sm font-semibold text-gray-600 mt-1">{{ $invoice->keterangan ?: '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
                    <span>✅</span> <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl">
                    <ul class="list-disc pl-5 text-sm font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header Action & Tabel Buku --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Buku Masuk</h3>
                        <p class="text-sm text-gray-500">Total: <span class="font-black text-indigo-600">{{ $invoice->buku->sum('jumlah_eksemplar') }}</span> eksemplar buku dari faktur ini.</p>
                    </div>
                    <button @click="openCreateBuku = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors cursor-pointer flex items-center gap-2 whitespace-nowrap">
                        <span>➕</span> Tambah Buku
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Judul Buku & ISBN</th>
                                <th class="px-6 py-4">Penerbit</th>
                                <th class="px-6 py-4 text-center">Tahun (Edisi)</th>
                                <th class="px-6 py-4 text-center">Eksemplar</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoice->buku as $b)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-gray-800 text-base">{{ $b->judul_buku }}</div>
                                        <div class="text-[11px] text-gray-500 font-bold mt-0.5">ISBN: {{ $b->isbn ?: 'Tidak ada' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $b->penerbit }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-600">{{ $b->tahun_terbit }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-lg font-black text-sm">{{ $b->jumlah_eksemplar }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('perpustakaan.invoice.buku.destroy', [$invoice->id, $b->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus buku ini dari daftar?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Buku">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                        <span class="text-5xl block mb-3">📚</span>
                                        <p class="font-bold text-lg text-gray-700">Belum ada rincian buku.</p>
                                        <p class="text-sm">Silakan klik Tambah Buku untuk menginput daftar buku dari nota ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL FORM TAMBAH BUKU --}}
        <div x-show="openCreateBuku" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden" @click.away="openCreateBuku = false">
                <div class="p-6 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-indigo-900">➕ Tambah Rincian Buku</h3>
                    <button @click="openCreateBuku = false" class="text-indigo-400 hover:text-indigo-600 text-2xl font-bold transition-colors cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('perpustakaan.invoice.buku.store', $invoice->id) }}" method="POST">
                    @csrf
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul_buku" required placeholder="Contoh: Matematika Dasar Vol 1" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">ISBN (Opsional)</label>
                            <input type="text" name="isbn" placeholder="Contoh: 978-623-XXXX" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Penerbit <span class="text-rose-500">*</span></label>
                                <input type="text" name="penerbit" required placeholder="Erlangga, dsb" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tahun Terbit <span class="text-rose-500">*</span></label>
                                <input type="text" name="tahun_terbit" required placeholder="2026" maxlength="4" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Eksemplar <span class="text-rose-500">*</span></label>
                            <input type="number" name="jumlah_eksemplar" required min="1" value="1" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" @click="openCreateBuku = false" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-colors">🚀 Tambah Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
