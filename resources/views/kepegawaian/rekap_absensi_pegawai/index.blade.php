<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="text-3xl">⏱️</span> {{ __('Rekap Absensi Pegawai') }}
            </h2>
        </div>
    </x-slot>

    <div x-data="absensiApp()" class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header Action --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Kehadiran Pegawai</h3>
                    <p class="text-sm text-gray-500">Kelola dan rekapitulasi data absensi jam kerja pegawai.</p>
                </div>
                <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                    <a href="{{ route('kepegawaian.rekap-absensi.downloadTemplate') }}" class="px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl shadow-sm transition-colors cursor-pointer flex items-center gap-2">
                        <span>📥</span> Template
                    </a>
                    <button @click="openImport = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors cursor-pointer flex items-center gap-2">
                        <span>📄</span> Import
                    </button>
                    <button @click="openCreate = true" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition-colors cursor-pointer flex items-center gap-2">
                        <span>➕</span> Tambah
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
                        <thead class="bg-gray-50 border-b border-gray-100 text-gray-600 font-bold uppercase text-[11px]">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Pegawai</th>
                                <th class="px-6 py-4">Jam Datang</th>
                                <th class="px-6 py-4">Jam Pulang</th>
                                <th class="px-6 py-4">Jumlah Jam Kerja</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($absensis as $absen)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-semibold">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $absen->pegawai->nama_lengkap ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-500 font-medium">NIP: {{ $absen->pegawai->nip ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($absen->jam_datang)
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md font-bold text-xs">{{ \Carbon\Carbon::parse($absen->jam_datang)->format('H:i') }}</span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($absen->jam_pulang)
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 rounded-md font-bold text-xs">{{ \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i') }}</span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($absen->jumlah_jam_kerja)
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-black text-[11px]">{{ $absen->jumlah_jam_kerja }}</span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="editData({{ $absen->toJson() }})" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer" title="Edit">✏️</button>
                                            <button @click="confirmDelete('{{ route('kepegawaian.rekap-absensi.destroy', $absen->id) }}', '{{ $absen->pegawai->nama_lengkap ?? '' }}', '{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}')" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <span class="text-4xl block mb-2">📭</span>
                                        <p class="font-bold">Belum ada data rekap absensi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL CREATE & EDIT --}}
        <div x-show="openCreate" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden" @click.away="openCreate = false">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900" x-text="isEdit ? '✏️ Edit Absensi Pegawai' : '➕ Tambah Absensi Pegawai'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-rose-500 text-2xl font-bold transition-colors cursor-pointer">&times;</button>
                </div>

                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        @method('PUT')
                    </template>

                    <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Absensi <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal" x-model="formData.tanggal" required class="w-full text-sm font-semibold rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Pegawai <span class="text-rose-500">*</span></label>
                                <select name="pegawai_id" x-model="formData.pegawai_id" @change="onPegawaiChange()" required class="w-full text-sm font-semibold rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 px-4 py-3">
                                    <option value="">-- Pilih Pegawai --</option>
                                    <template x-for="pegawai in daftarPegawai" :key="pegawai.id">
                                        <option :value="pegawai.id" x-text="pegawai.nama_lengkap"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Auto-fill Pegawai Info Box --}}
                        <div x-show="formData.pegawai_id" class="p-5 bg-indigo-50/50 border border-indigo-100 rounded-2xl grid grid-cols-1 md:grid-cols-3 gap-4" style="display: none;" x-transition>
                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">NIP</label>
                                <div class="text-sm font-bold text-indigo-900" x-text="pegawaiInfo.nip || '-'"></div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Status Pegawai</label>
                                <div class="text-sm font-bold text-indigo-900" x-text="pegawaiInfo.status_pegawai || '-'"></div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Jenis PTK</label>
                                <div class="text-sm font-bold text-indigo-900" x-text="pegawaiInfo.jenis_ptk || '-'"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-emerald-700 mb-2">Jam Datang</label>
                                <input type="time" name="jam_datang" x-model="formData.jam_datang" @change="calculateJamKerja()" class="w-full text-sm font-bold rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 bg-emerald-50/50 px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-rose-700 mb-2">Jam Pulang</label>
                                <input type="time" name="jam_pulang" x-model="formData.jam_pulang" @change="calculateJamKerja()" class="w-full text-sm font-bold rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500 bg-rose-50/50 px-4 py-3">
                            </div>
                        </div>

                        {{-- Auto Kalkulasi Preview --}}
                        <div x-show="calculatedHours" class="p-4 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-between" style="display: none;" x-transition>
                            <span class="text-xs font-bold text-slate-500">Total Jam Kerja Terhitung:</span>
                            <span class="text-sm font-black text-slate-800" x-text="calculatedHours"></span>
                        </div>

                    </div>
                    
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-colors">💾 Simpan Absensi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL DELETE CONFIRMATION --}}
        <div x-show="openDelete" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl overflow-hidden p-6 text-center" @click.away="openDelete = false">
                <div class="w-20 h-20 mx-auto bg-rose-100 rounded-full flex items-center justify-center mb-4 border-[6px] border-rose-50">
                    <span class="text-4xl">🗑️</span>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Rekap Absensi?</h3>
                <p class="text-sm text-gray-500 mb-2">Tindakan ini akan menghapus data absensi secara permanen.</p>
                <div class="p-3 bg-gray-50 rounded-xl mb-6">
                    <p class="text-sm font-bold text-gray-800" x-text="deleteDetails.pegawai"></p>
                    <p class="text-xs text-gray-500 mt-1">Tgl: <span x-text="deleteDetails.tanggal"></span></p>
                </div>
                
                <form :action="deleteAction" method="POST" class="flex gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDelete = false" class="flex-1 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-md transition-colors">Ya, Hapus</button>
                </form>
            </div>
        </div>
        {{-- MODAL IMPORT EXCEL --}}
        <div x-show="openImport" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden" @click.away="openImport = false">
                <div class="p-6 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-emerald-900">📄 Import Absensi</h3>
                    <button @click="openImport = false" class="text-emerald-400 hover:text-emerald-600 text-2xl font-bold transition-colors cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('kepegawaian.rekap-absensi.importExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Excel (.xlsx)</label>
                        <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="w-full text-sm rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 cursor-pointer">
                        <p class="text-xs text-gray-500 mt-2">Gunakan format template yang telah disediakan. Kolom jam boleh dikosongkan jika absen.</p>
                    </div>
                    
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" @click="openImport = false" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-colors">🚀 Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('absensiApp', () => ({
                daftarPegawai: @json($pegawais),
                openCreate: false,
                openImport: false,
                isEdit: false,
                formAction: '{{ route('kepegawaian.rekap-absensi.store') }}',
                
                formData: {
                    id: '',
                    tanggal: '{{ date('Y-m-d') }}',
                    pegawai_id: '',
                    jam_datang: '',
                    jam_pulang: ''
                },
                
                pegawaiInfo: {
                    nip: '',
                    status_pegawai: '',
                    jenis_ptk: ''
                },
                
                calculatedHours: '',

                openDelete: false,
                deleteAction: '',
                deleteDetails: {
                    pegawai: '',
                    tanggal: ''
                },

                onPegawaiChange() {
                    let p = this.daftarPegawai.find(x => x.id == this.formData.pegawai_id);
                    if (p) {
                        this.pegawaiInfo.nip = p.nip;
                        this.pegawaiInfo.status_pegawai = p.status_pegawai;
                        this.pegawaiInfo.jenis_ptk = p.jenis_ptk;
                    } else {
                        this.pegawaiInfo = { nip: '', status_pegawai: '', jenis_ptk: '' };
                    }
                },

                calculateJamKerja() {
                    if (this.formData.jam_datang && this.formData.jam_pulang) {
                        let [h1, m1] = this.formData.jam_datang.split(':').map(Number);
                        let [h2, m2] = this.formData.jam_pulang.split(':').map(Number);
                        
                        let totalMin1 = h1 * 60 + m1;
                        let totalMin2 = h2 * 60 + m2;
                        
                        if (totalMin2 < totalMin1) {
                            totalMin2 += 24 * 60; // Pulang keesokan hari
                        }
                        
                        let diff = totalMin2 - totalMin1;
                        let jam = Math.floor(diff / 60);
                        let menit = diff % 60;
                        
                        let res = [];
                        if (jam > 0) res.push(jam + " Jam");
                        if (menit > 0) res.push(menit + " Menit");
                        
                        this.calculatedHours = res.length ? res.join(" ") : "0 Menit";
                    } else {
                        this.calculatedHours = '';
                    }
                },

                editData(data) {
                    this.isEdit = true;
                    this.formAction = `/kepegawaian/rekap-absensi/${data.id}`;
                    this.formData.id = data.id;
                    this.formData.tanggal = data.tanggal.substring(0,10);
                    this.formData.pegawai_id = data.pegawai_id;
                    this.formData.jam_datang = data.jam_datang ? data.jam_datang.substring(0,5) : '';
                    this.formData.jam_pulang = data.jam_pulang ? data.jam_pulang.substring(0,5) : '';
                    
                    this.onPegawaiChange();
                    this.calculateJamKerja();
                    this.openCreate = true;
                },

                closeModal() {
                    this.openCreate = false;
                    setTimeout(() => {
                        this.isEdit = false;
                        this.formAction = '{{ route('kepegawaian.rekap-absensi.store') }}';
                        this.formData = {
                            id: '',
                            tanggal: '{{ date('Y-m-d') }}',
                            pegawai_id: '',
                            jam_datang: '',
                            jam_pulang: ''
                        };
                        this.pegawaiInfo = { nip: '', status_pegawai: '', jenis_ptk: '' };
                        this.calculatedHours = '';
                    }, 300);
                },

                confirmDelete(url, pegawai, tanggal) {
                    this.deleteAction = url;
                    this.deleteDetails.pegawai = pegawai;
                    this.deleteDetails.tanggal = tanggal;
                    this.openDelete = true;
                }
            }));
        });
    </script>
</x-app-layout>
