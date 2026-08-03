<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Agenda Surat Keluar') }}</h2>
    </x-slot>

    {{-- Quill Rich Text Editor CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <div x-data="{ 
        openCreate: false,
        quill: null,
        initQuill() {
            if (!this.quill && this.$refs.editorContainer) {
                this.quill = new Quill(this.$refs.editorContainer, {
                    theme: 'snow',
                    placeholder: 'Ketik isi surat resmi di sini... (Bisa Tebal, Miring, Poin-poin, Paragraf, dll.)',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['clean']
                        ]
                    }
                });
                this.quill.on('text-change', () => {
                    this.$refs.hiddenIsiSurat.value = this.quill.root.innerHTML;
                });
            }
        },
        insertTemplate(type) {
            let html = '';
            if (type === 'sk') {
                html = '<p style="text-align: center;"><strong>SURAT KEPUTUSAN KEPALA SEKOLAH</strong></p><p style="text-align: center;">TENTANG<br><strong>[PENETAPAN / PERIHAL SK]</strong></p><p><strong>Menimbang:</strong></p><ol><li>bahwa dalam rangka kelancaran kegiatan sekolah...</li><li>bahwa nama yang tercantum dalam keputusan ini dipandang mampu dan memenuhi syarat...</li></ol><p><strong>Mengingat:</strong></p><ol><li>Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</li><li>Peraturan Pemerintah Nomor 19 Tahun 2005 tentang Standar Nasional Pendidikan;</li></ol><p style="text-align: center;"><strong>MEMUTUSKAN:</strong></p><p><strong>Menetapkan:</strong></p><p><strong>PERTAMA:</strong> Menugaskan Saudara/i ... sebagai ...</p><p><strong>KEDUA:</strong> Keputusan ini berlaku sejak tanggal ditetapkan dengan ketentuan apabila terdapat kekeliruan akan diperbaiki sebagaimana mestinya.</p>';
            } else if (type === 'sppd') {
                html = '<p style="text-align: center;"><strong>SURAT PERINTAH TUGAS (SPPD)</strong></p><p>Yang bertanda tangan di bawah ini Kepala Sekolah, memberi tugas kepada:</p><p><strong>Nama Pegawai:</strong> [Nama Pegawai]<br><strong>NIP:</strong> [NIP Pegawai]<br><strong>Jabatan:</strong> [Jabatan Pegawai]</p><p><strong>Untuk:</strong></p><ol><li>Melaksanakan tugas / menghadiri kegiatan: [Nama Kegiatan]</li><li>Waktu Pelaksanaan: [Tanggal Kegiatan]</li><li>Tempat Tujuan: [Lokasi Tujuan]</li></ol><p>Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh rasa tanggung jawab.</p>';
            } else if (type === 'keterangan') {
                html = '<p style="text-align: center;"><strong>SURAT KETERANGAN SISWA AKTIF</strong></p><p>Yang bertanda tangan di bawah ini Kepala Sekolah, menerangkan bahwa:</p><p><strong>Nama Siswa:</strong> [Nama Siswa]<br><strong>NISN / NIS:</strong> [NISN Siswa]<br><strong>Kelas:</strong> [Kelas Siswa]</p><p>Adalah benar siswa tersebut terdaftar sebagai siswa aktif pada sekolah ini untuk Tahun Ajaran 2026/2027.</p><p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>';
            } else if (type === 'undangan') {
                html = '<p>Kepada Yth.<br><strong>Bapak/Ibu [Tujuan Undangan]</strong><br>di Tempat</p><p>Dengan hormat,</p><p>Sehubungan dengan agenda kegiatan sekolah, kami mengundang Bapak/Ibu untuk hadir pada acara yang akan dilaksanakan pada:</p><p><strong>Hari / Tanggal:</strong> [Hari, Tanggal]<br><strong>Waktu:</strong> [Waktu WIB]<br><strong>Tempat:</strong> [Lokasi Rapat]<br><strong>Agenda:</strong> [Topik Rapat]</p><p>Demikian surat undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>';
            }
            if (this.quill) {
                this.quill.clipboard.dangerouslyPasteHTML(html);
                this.$refs.hiddenIsiSurat.value = this.quill.root.innerHTML;
            }
        }
    }" 
    x-init="$watch('openCreate', value => { if (value) setTimeout(() => initQuill(), 100); })"
    class="py-6 bg-slate-100 min-h-[calc(100vh-64px)]">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl flex items-center gap-2">✅ {{ session('success') }}</div>
            @endif

            <div class="bg-white p-4 rounded-xl border border-gray-200 flex justify-between items-center shadow-sm">
                <div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase">Draf & Surat Keluar Resmi</h3>
                    <p class="text-[11px] text-gray-400">Ketik surat langsung di sistem dengan editor teks mini Word & sertakan Excel jika memiliki lampiran tabel.</p>
                </div>
                <button @click="openCreate = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg cursor-pointer flex items-center gap-2">
                    <span>📝</span> Buat Usulan Surat
                </button>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b">
                            <th class="p-4">Nomor / Perihal</th>
                            <th class="p-4">Tujuan</th>
                            <th class="p-4 text-center">Lampiran Excel</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-xs text-gray-700">
                        @forelse($suratKeluar as $item)
                            <tr class="hover:bg-gray-50/50">
                                <td class="p-4">
                                    <div class="font-bold font-mono text-gray-900">{{ $item->nomor_surat ?? '✨ [Belum Disetujui]' }}</div>
                                    <div class="font-semibold text-gray-600 mt-0.5">{{ $item->perihal }}</div>
                                </td>
                                <td class="p-4 font-medium">{{ $item->tujuan_surat }}</td>
                                <td class="p-4 text-center">
                                    @if($item->header_1)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold">📋 Aktif</span>
                                    @else
                                        <span class="text-gray-400 italic">Tidak Ada</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $item->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $item->status }}</span>
                                </td>
                                <td class="p-4 text-center space-x-1">
                                    @if($item->status == 'Menunggu Persetujuan')
                                        <form action="{{ route('surat.keluar.setujui', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg cursor-pointer shadow-sm">Setujui</button>
                                        </form>
                                    @elseif($item->status == 'Disetujui')
                                        <a href="{{ route('surat.keluar.cetak', $item->id) }}" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg block sm:inline shadow-sm">🖨️ Cetak PDF</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-8 text-center italic text-gray-400">Belum ada data surat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Input Draf Surat dengan Quill Editor --}}
        <div x-show="openCreate" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;" x-transition>
            <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] shadow-2xl border border-gray-100 overflow-hidden flex flex-col" @click.away="openCreate = false">
                
                <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50/70 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-sm font-black text-indigo-900 uppercase">✍️ Buat Draf Surat Keluar Resmi</h3>
                        <p class="text-[11px] font-medium text-indigo-600 mt-0.5">Ketik isi surat secara langsung menggunakan Editor Mini Word di bawah.</p>
                    </div>
                    <button type="button" @click="openCreate = false" class="text-gray-400 hover:text-rose-500 font-bold text-2xl cursor-pointer">&times;</button>
                </div>
                
                <form action="{{ route('surat.keluar.store') }}" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-4 text-xs bg-white flex-1 custom-scrollbar">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Klasifikasi Format *</label>
                            <select name="jenis_surat_id" required class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                                <option value="">-- Pilih Format Klasifikasi --</option>
                                @foreach($jenisSurat as $js)
                                    <option value="{{ $js->id }}">{{ $js->kode_klasifikasi }} - {{ $js->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Tujuan Surat / Kepada Yth. *</label>
                            <input type="text" name="tujuan_surat" required placeholder="Contoh: Bapak/Ibu Orang Tua / Wali Murid" class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Tanggal Surat *</label>
                            <input type="date" name="tanggal_surat" required class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Metode TTD *</label>
                            <select name="metode_ttd" required class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                                <option value="Digital">Digital (Stempel & TTD Otomatis)</option>
                                <option value="Basah">Basah (Manual Fisik)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Penandatangan *</label>
                            <select name="penandatangan_id" required class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                                @foreach($daftarKepsek as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Perihal Utama *</label>
                        <input type="text" name="perihal" required placeholder="Contoh: Undangan Rapat Evaluasi Pembelajaran" class="w-full rounded-xl border-gray-300 text-xs font-semibold py-2.5">
                    </div>

                    {{-- Quill Rich Text Editor Container --}}
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1">
                            <label class="block font-bold text-gray-700">Isi Surat Resmi (Ketik Langsung ala Word) *</label>
                            
                            {{-- Tombol Sisip Template Instan --}}
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[10px] font-bold text-gray-400">⚡ Sisip Template:</span>
                                <button type="button" @click="insertTemplate('sk')" class="px-2 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Struktur Surat Keputusan (SK)">
                                    📜 SK Kepsek
                                </button>
                                <button type="button" @click="insertTemplate('sppd')" class="px-2 py-0.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Struktur Surat Tugas / SPPD">
                                    ✈️ SPPD / Tugas
                                </button>
                                <button type="button" @click="insertTemplate('keterangan')" class="px-2 py-0.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Surat Keterangan Siswa Aktif">
                                    🎓 Keterangan Siswa
                                </button>
                                <button type="button" @click="insertTemplate('undangan')" class="px-2 py-0.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-800 border border-indigo-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Surat Undangan Rapat">
                                    ✉️ Undangan
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="isi_surat" x-ref="hiddenIsiSurat" required>
                        <div x-ref="editorContainer" class="bg-white rounded-b-xl border border-gray-300 min-h-[180px] text-sm"></div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-300 space-y-1">
                        <label class="block font-bold text-slate-800">📎 Upload Excel Lampiran Tabel (Opsional)</label>
                        <input type="file" name="file_excel" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[11px] text-gray-500">Sistem otomatis membaca baris pertama Excel sebagai judul header kolom tabel lampiran.</p>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t sticky bottom-0 bg-white py-2 z-20">
                        <button type="button" @click="openCreate = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md text-xs transition-colors cursor-pointer flex items-center gap-2">
                            <span>🚀</span> Kirim Usulan Draf Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>