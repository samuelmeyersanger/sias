<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    <span class="text-3xl">📦</span> {{ __('Inventaris Barang Habis Pakai') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-1">Kelola data stok masuk dan keluar untuk barang habis pakai.</p>
            </div>
            
            <button @click="$dispatch('open-modal', 'create-barang-modal')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors flex items-center gap-2">
                <span>➕</span> Tambah Barang Baru
            </button>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        searchQuery: '',
        editData: {},
        deleteId: null,
        
        editBarang(barang) {
            this.editData = { ...barang };
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-barang-modal' }));
        },
        
        confirmDelete(id) {
            this.deleteId = id;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-barang-modal' }));
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="text-emerald-500 text-xl">✅</span>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-rose-500 text-xl">⚠️</span>
                    <p class="font-bold text-sm">Terjadi kesalahan input:</p>
                </div>
                <ul class="list-disc list-inside text-sm font-medium pl-8 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                
                <!-- Toolbar -->
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="relative w-full sm:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">🔍</span>
                        </div>
                        <input x-model="searchQuery" type="text" placeholder="Cari nama barang atau kode..." class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider">Barang & Kategori</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-center">Sisa Stok</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($barangs as $barang)
                            <tr class="hover:bg-gray-50/50 transition-colors" x-show="!searchQuery || '{{ strtolower($barang->nama_barang . ' ' . $barang->kode_barang) }}'.includes(searchQuery.toLowerCase())">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold font-mono border border-gray-200">
                                        {{ $barang->kode_barang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-sm">{{ $barang->nama_barang }}</div>
                                    <div class="text-xs font-medium text-gray-500 mt-0.5">
                                        {{ $barang->kategori ?? 'Tanpa Kategori' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-black {{ $barang->stok <= $barang->stok_minimal ? 'text-rose-600' : 'text-emerald-600' }}">
                                            {{ $barang->stok }}
                                        </span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $barang->satuan ?? 'Item' }}</span>
                                    </div>
                                    @if($barang->stok <= $barang->stok_minimal)
                                    <div class="mt-1">
                                        <span class="inline-block px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-[10px] font-bold border border-rose-200">
                                            Stok Menipis (Min: {{ $barang->stok_minimal }})
                                        </span>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('sarpras.barang-habis-pakai.show', $barang->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition-colors border border-indigo-200">
                                            📊 Kelola Stok
                                        </a>
                                        <button @click="editBarang({{ json_encode($barang) }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors border border-amber-200" title="Edit">
                                            ✏️
                                        </button>
                                        <button @click="confirmDelete({{ $barang->id }})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors border border-rose-200" title="Hapus">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-4xl mb-3">📦</div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum ada barang</h3>
                                    <p class="text-gray-500 text-sm font-medium">Mulai tambahkan barang habis pakai pertama Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <x-modal name="create-barang-modal" :show="false" maxWidth="xl">
            <form action="{{ route('sarpras.barang-habis-pakai.store') }}" method="POST" class="p-6">
                @csrf
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span>➕</span> Tambah Barang Baru
                    </h2>
                    <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kode Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_barang" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm" placeholder="Misal: ATK-001">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm" placeholder="Misal: Kertas HVS A4 70gsm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm" placeholder="Misal: ATK">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Satuan</label>
                            <input type="text" name="satuan" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm" placeholder="Misal: Rim">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Stok Minimal (Peringatan)</label>
                        <input type="number" name="stok_minimal" value="0" min="0" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                        <p class="text-xs text-gray-500 mt-1 font-medium">Sistem akan memberi tahu jika stok berada di bawah angka ini.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi / Keterangan Tambahan</label>
                        <textarea name="deskripsi" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Edit Modal -->
        <x-modal name="edit-barang-modal" :show="false" maxWidth="xl">
            <form :action="'{{ url('sarpras/barang-habis-pakai') }}/' + editData.id" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span>✏️</span> Edit Barang
                    </h2>
                    <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kode Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_barang" x-model="editData.kode_barang" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" x-model="editData.nama_barang" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" x-model="editData.kategori" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Satuan</label>
                            <input type="text" name="satuan" x-model="editData.satuan" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Stok Minimal</label>
                        <input type="number" name="stok_minimal" x-model="editData.stok_minimal" min="0" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Modal -->
        <x-modal name="delete-barang-modal" :show="false" maxWidth="md">
            <div class="p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-3xl">⚠️</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Barang?</h3>
                    <p class="text-sm font-medium text-gray-500 mb-6">Anda yakin ingin menghapus barang ini? Data transaksi yang terkait juga akan tersembunyi.</p>
                </div>
                
                <form :action="'{{ url('sarpras/barang-habis-pakai') }}/' + deleteId" method="POST" class="flex justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 shadow-sm transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>
