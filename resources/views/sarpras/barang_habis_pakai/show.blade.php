<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('sarpras.barang-habis-pakai.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 shadow-sm transition-colors" title="Kembali ke Daftar Barang">
                <span class="text-xl font-bold">←</span>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <span class="text-3xl">📦</span> {{ $barang->nama_barang }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-1">Detail Barang & Riwayat Mutasi Stok.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        openMasukModal: false,
        openKeluarModal: false,
        
        stokCurrent: {{ $barang->stok }},
        jumlahMasuk: 1,
        jumlahKeluar: 1
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="text-emerald-500 text-xl">✅</span>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="text-rose-500 text-xl">⚠️</span>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Info & Action Card -->
            <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100 flex flex-col md:flex-row justify-between gap-6">
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full md:w-2/3">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kode Barang</p>
                        <p class="font-mono font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded-lg inline-block border border-gray-200">{{ $barang->kode_barang }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kategori</p>
                        <p class="font-bold text-gray-900">{{ $barang->kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Saat Ini</p>
                        <p class="text-3xl font-black {{ $barang->stok <= $barang->stok_minimal ? 'text-rose-600' : 'text-indigo-600' }} flex items-baseline gap-1">
                            {{ $barang->stok }} <span class="text-sm font-bold text-gray-500 uppercase">{{ $barang->satuan ?? 'Item' }}</span>
                        </p>
                        @if($barang->stok <= $barang->stok_minimal)
                            <p class="text-xs font-bold text-rose-500 mt-1">Stok Menipis (Min: {{ $barang->stok_minimal }})</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Deskripsi</p>
                        <p class="text-sm font-medium text-gray-700">{{ $barang->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 justify-center md:border-l md:border-gray-100 md:pl-6 w-full md:w-1/3">
                    <button @click="openMasukModal = true" class="w-full px-5 py-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                        <span class="text-lg">⬇️</span> Catat Barang Masuk (Restock)
                    </button>
                    <button @click="openKeluarModal = true" class="w-full px-5 py-3 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 font-bold rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                        <span class="text-lg">⬆️</span> Catat Barang Keluar (Diambil)
                    </button>
                </div>
            </div>

            <!-- Transaction History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <span>📜</span> Riwayat Mutasi (Keluar Masuk)
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Jenis Transaksi</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-center">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Keterangan / Penerima</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Diinput Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transaksis as $trx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] font-medium text-gray-400 mt-0.5">{{ $trx->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trx->jenis_transaksi === 'masuk')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase flex items-center gap-1 w-max">
                                            ⬇️ Masuk
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase flex items-center gap-1 w-max">
                                            ⬆️ Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="font-black text-lg {{ $trx->jenis_transaksi === 'masuk' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $trx->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $trx->jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($trx->jenis_transaksi === 'keluar' && $trx->pegawai)
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded uppercase">Penerima</span>
                                            <span class="text-sm font-bold text-gray-900">{{ $trx->pegawai->nama_lengkap }}</span>
                                        </div>
                                    @endif
                                    <p class="text-sm font-medium text-gray-600">{{ $trx->keterangan ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-medium text-gray-500">{{ $trx->user->name ?? 'Sistem' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-3xl mb-2">📭</div>
                                    <p class="text-gray-500 font-medium">Belum ada riwayat transaksi mutasi untuk barang ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Barang Masuk -->
        <x-modal name="masuk-modal" :show="openMasukModal" maxWidth="md">
            <form action="{{ route('sarpras.barang-habis-pakai.transaksi.store', $barang->id) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="jenis_transaksi" value="masuk">
                
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-emerald-700 flex items-center gap-2">
                        <span>⬇️</span> Form Barang Masuk
                    </h2>
                    <button type="button" @click="openMasukModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Masuk <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah (Stok Bertambah) <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="jumlah" x-model="jumlahMasuk" min="1" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-lg font-bold shadow-sm">
                            <span class="font-bold text-gray-500">{{ $barang->satuan ?? 'Item' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan / Sumber Barang</label>
                        <textarea name="keterangan" rows="2" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium shadow-sm" placeholder="Misal: Pembelian dari supplier X..."></textarea>
                    </div>
                    
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100 text-center">
                        <p class="text-sm font-bold text-emerald-800">Estimasi Stok Akhir: <span class="text-xl" x-text="stokCurrent + parseInt(jumlahMasuk || 0)"></span></p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="openMasukModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-sm transition-colors">
                        Simpan Barang Masuk
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Barang Keluar -->
        <x-modal name="keluar-modal" :show="openKeluarModal" maxWidth="md">
            <form action="{{ route('sarpras.barang-habis-pakai.transaksi.store', $barang->id) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="jenis_transaksi" value="keluar">
                
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-amber-700 flex items-center gap-2">
                        <span>⬆️</span> Form Barang Keluar
                    </h2>
                    <button type="button" @click="openKeluarModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Keluar <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm font-medium shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah (Stok Berkurang) <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="jumlah" x-model="jumlahKeluar" min="1" :max="stokCurrent" required class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-lg font-bold shadow-sm">
                            <span class="font-bold text-gray-500">{{ $barang->satuan ?? 'Item' }}</span>
                        </div>
                        <p class="text-xs font-bold text-rose-500 mt-1" x-show="jumlahKeluar > stokCurrent">Jumlah melebihi stok yang ada!</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Penerima Barang / Pegawai</label>
                        <select name="pegawai_id" class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm font-medium shadow-sm">
                            <option value="">-- Tanpa Penerima Khusus --</option>
                            @foreach($pegawais as $pgw)
                                <option value="{{ $pgw->id }}">{{ $pgw->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan / Keperluan</label>
                        <textarea name="keterangan" rows="2" class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500 text-sm font-medium shadow-sm" placeholder="Misal: Diambil untuk kegiatan ujian..."></textarea>
                    </div>
                    
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-center">
                        <p class="text-sm font-bold text-amber-800">Estimasi Sisa Stok: <span class="text-xl" x-text="stokCurrent - parseInt(jumlahKeluar || 0)"></span></p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="openKeluarModal = false" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="jumlahKeluar > stokCurrent" class="px-5 py-2.5 text-sm font-bold text-white bg-amber-600 rounded-xl hover:bg-amber-700 shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Simpan Barang Keluar
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
