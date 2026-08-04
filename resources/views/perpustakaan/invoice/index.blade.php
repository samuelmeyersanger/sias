<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="text-3xl">📚</span> {{ __('Invoice Pembelian Buku') }}
            </h2>
        </div>
    </x-slot>

    <div x-data="invoiceApp()" class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header Action --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Nota / Faktur</h3>
                    <p class="text-sm text-gray-500">Kelola catatan pembelian buku untuk inventaris perpustakaan.</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('perpustakaan.invoice.index') }}" method="GET" class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor / suplier..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                    </form>
                    <button @click="openCreate = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors cursor-pointer flex items-center gap-2 whitespace-nowrap">
                        <span>➕</span> Tambah Invoice
                    </button>
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

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 font-bold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nomor Invoice</th>
                                <th class="px-6 py-4">Tanggal Pembelian</th>
                                <th class="px-6 py-4">Suplier / Toko</th>
                                <th class="px-6 py-4 text-center">Total Item Buku</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-indigo-900">{{ $inv->nomor_invoice }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $inv->tanggal_invoice->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $inv->nama_suplier }}</div>
                                        @if($inv->keterangan)
                                            <div class="text-[11px] text-gray-500 font-medium truncate max-w-[200px]">{{ $inv->keterangan }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-lg font-black text-xs">{{ $inv->buku_count }} Judul</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('perpustakaan.invoice.show', $inv->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Rincian Buku">📖</a>
                                            <button @click="editData({{ $inv->toJson() }})" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer" title="Edit Invoice">✏️</button>
                                            <form action="{{ route('perpustakaan.invoice.destroy', $inv->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini beserta seluruh rincian bukunya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                        <span class="text-5xl block mb-3">🧾</span>
                                        <p class="font-bold text-lg text-gray-700">Belum ada data invoice.</p>
                                        <p class="text-sm">Silakan tambah invoice baru untuk mulai menginput data buku.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($invoices->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL FORM INVOICE --}}
        <div x-show="openCreate" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden" @click.away="openCreate = false">
                <div class="p-6 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-indigo-900" x-text="isEdit ? '✏️ Edit Invoice' : '➕ Tambah Invoice Baru'"></h3>
                    <button @click="openCreate = false" class="text-indigo-400 hover:text-indigo-600 text-2xl font-bold transition-colors cursor-pointer">&times;</button>
                </div>

                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Invoice <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor_invoice" x-model="formData.nomor_invoice" required placeholder="Contoh: INV-2026-001" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Invoice <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_invoice" x-model="formData.tanggal_invoice" required class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Suplier / Toko <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_suplier" x-model="formData.nama_suplier" required placeholder="Nama penerbit atau toko buku" class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" x-model="formData.keterangan" rows="2" placeholder="Catatan tambahan..." class="w-full text-sm rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white px-4 py-3 shadow-sm"></textarea>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" @click="openCreate = false" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-colors" x-text="isEdit ? '💾 Simpan Perubahan' : '🚀 Tambah Invoice'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('invoiceApp', () => ({
                openCreate: false,
                isEdit: false,
                formAction: '{{ route('perpustakaan.invoice.store') }}',
                formData: {
                    nomor_invoice: '',
                    tanggal_invoice: '',
                    nama_suplier: '',
                    keterangan: ''
                },
                
                editData(item) {
                    this.isEdit = true;
                    // Ambil tanggal dengan format Y-m-d untuk input type="date"
                    let d = new Date(item.tanggal_invoice);
                    let formattedDate = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                    
                    this.formData = {
                        nomor_invoice: item.nomor_invoice,
                        tanggal_invoice: formattedDate,
                        nama_suplier: item.nama_suplier,
                        keterangan: item.keterangan || ''
                    };
                    this.formAction = `/perpustakaan/invoice/${item.id}`;
                    this.openCreate = true;
                }
            }));
        });
    </script>
</x-app-layout>
