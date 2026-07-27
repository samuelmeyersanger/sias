<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            📋 {{ __('Data Ekstrakurikuler') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <style>
                /* CSS Untuk Print PDF / Kertas */
                @page { size: 13in 8.5in; margin: 1cm; } /* Folio Landscape */
                
                @media print {
                    .no-print { display: none !important; }
                    body { background: white !important; padding: 0 !important; }
                    .print-table { width: 100%; border-collapse: collapse; }
                    .print-table th, .print-table td { border: 1px solid #000 !important; padding: 8px; font-size: 14px; }
                    .print-table th { background-color: #f3f4f6 !important; text-align: center; }
                    .print-header { text-align: center; margin-bottom: 20px; }
                    .show-on-print { display: block !important; }
                    .no-print-wrapper { box-shadow: none !important; margin: 0 !important; padding: 0 !important; background: transparent !important; border: none !important;}
                    
                    /* Hapus styling badge khusus untuk print agar terlihat normal di kertas */
                    .print-badge { background: transparent !important; color: black !important; padding: 0 !important; border-radius: 0 !important;}
                    .print-italic { font-style: normal !important; color: black !important; }
                }
            </style>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 no-print-wrapper">
                
                <!-- Area Pencarian (Sembunyikan saat di-print) -->
                <div class="no-print p-8 bg-indigo-900 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="relative z-10 text-center">
                        <h3 class="text-3xl font-black mb-2 text-white">Data Anggota Ekstrakurikuler 🎯</h3>
                        <p class="text-indigo-200 font-medium mb-6">
                            Tahun Ajaran Aktif: <span class="text-white">{{ $tahunAjaran }}</span> | Semester Aktif: <span class="text-white">{{ $semester }}</span>
                        </p>
                        
                        <form action="{{ route('ekskul.info_publik') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end justify-center max-w-2xl mx-auto">
                            <div class="flex-1 w-full text-left">
                                <label for="ekskul_id" class="block text-sm font-bold text-indigo-100 mb-2">Pilih Ekstrakurikuler</label>
                                <select name="ekskul_id" id="ekskul_id" class="w-full rounded-xl border-0 focus:ring-4 focus:ring-indigo-300 shadow-lg text-gray-800 font-medium py-3" required>
                                    <option value="">-- Silakan Pilih --</option>
                                    @foreach($ekskulList as $e)
                                        <option value="{{ $e->id }}" {{ request('ekskul_id') == $e->id ? 'selected' : '' }}>{{ $e->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-white hover:bg-gray-100 text-indigo-900 font-black py-3 px-8 rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                                TAMPILKAN 🚀
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Area Hasil Pencarian & Print -->
                <div class="p-8">
                    @if($selectedEkskul)
                        
                        <!-- Tombol Cetak -->
                        <div class="no-print flex justify-end mb-6 border-b pb-6 border-gray-100">
                            <button onclick="window.print()" class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md inline-flex items-center gap-2 transition-all transform hover:-translate-y-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak PDF
                            </button>
                        </div>

                        <!-- Bagian ini yang akan di-print -->
                        <div class="print-area">
                            <div class="print-header hidden show-on-print text-center mb-6">
                                <h1 style="font-size: 24px; font-weight: bold; margin: 0;">DATA EKSTRAKURIKULER {{ strtoupper($selectedEkskul->nama) }}</h1>
                                <p style="font-size: 14px; margin: 5px 0 0 0;">Tahun Ajaran: {{ $tahunAjaran }} | Semester: {{ $semester }}</p>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="w-full text-left border-collapse print-table">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-700">
                                            <th class="p-4 border-r border-gray-200 font-bold w-16 text-center">NO</th>
                                            <th class="p-4 border-r border-gray-200 font-bold w-64">NAMA LENGKAP SISWA</th>
                                            <th class="p-4 border-r border-gray-200 font-bold w-40 text-center">NO HP</th>
                                            <th class="p-4 border-r border-gray-200 font-bold w-24 text-center">KELAS</th>
                                            <th class="p-4 font-bold">MOTIVASI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($anggota as $index => $row)
                                            <tr class="border-b border-gray-100 hover:bg-indigo-50 transition-colors">
                                                <td class="p-4 border-r border-gray-100 text-center font-medium text-gray-600">{{ $index + 1 }}</td>
                                                <td class="p-4 border-r border-gray-100 font-bold text-gray-800">{{ $row->siswa->nama_lengkap ?? '-' }}</td>
                                                <td class="p-4 border-r border-gray-100 text-center font-medium text-gray-600">{{ $row->nomor_hp ?? '-' }}</td>
                                                <td class="p-4 border-r border-gray-100 text-center font-medium text-indigo-600">
                                                    <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full text-xs font-bold print-badge">{{ $row->siswa->kelas->nama_kelas ?? '-' }}</span>
                                                </td>
                                                <td class="p-4 text-sm text-gray-600 italic print-italic">"{{ $row->motivasi ?? '-' }}"</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-8 text-center text-gray-500 italic">Belum ada anggota yang terdaftar di ekstrakurikuler ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    @elseif(request()->has('ekskul_id'))
                        <div class="text-center p-8 bg-red-50 text-red-600 rounded-2xl border border-red-100 no-print">
                            <span class="text-4xl block mb-3">⚠️</span>
                            <span class="font-bold">Oops!</span> Ekstrakurikuler tidak ditemukan atau sudah dinonaktifkan.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
