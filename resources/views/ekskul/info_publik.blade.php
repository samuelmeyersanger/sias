<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ekstrakurikuler Publik</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            /* Memaksa elemen yang disembunyikan di layar agar muncul di print */
            .show-on-print { display: block !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-5 md:p-10">

    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg p-6 md:p-8 no-print-wrapper">
        
        <!-- Area Pencarian (Sembunyikan saat di-print) -->
        <div class="no-print border-b pb-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Data Ekstrakurikuler</h1>
            <p class="text-gray-600 mb-6">Tahun Ajaran Aktif: <span class="font-semibold">{{ $tahunAjaran }}</span> | Semester Aktif: <span class="font-semibold">{{ $semester }}</span></p>
            
            <form action="{{ route('ekskul.info_publik') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="ekskul_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Ekstrakurikuler</label>
                    <select name="ekskul_id" id="ekskul_id" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Silakan Pilih --</option>
                        @foreach($ekskulList as $e)
                            <option value="{{ $e->id }}" {{ request('ekskul_id') == $e->id ? 'selected' : '' }}>{{ $e->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors">
                    Tampilkan Data
                </button>
            </form>
        </div>

        <!-- Area Hasil Pencarian & Print -->
        @if($selectedEkskul)
            
            <!-- Tombol Cetak -->
            <div class="no-print flex justify-end mb-4">
                <button onclick="window.print()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded-lg inline-flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>

            <!-- Bagian ini yang akan di-print -->
            <div class="print-area">
                <div class="print-header hidden show-on-print text-center mb-6">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;">DATA EKSTRAKURIKULER {{ strtoupper($selectedEkskul->nama) }}</h1>
                    <p style="font-size: 14px; margin: 5px 0 0 0;">Tahun Ajaran: {{ $tahunAjaran }} | Semester: {{ $semester }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse print-table">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <th class="p-3 border border-gray-300 font-semibold w-12 text-center">NO</th>
                                <th class="p-3 border border-gray-300 font-semibold w-64">NAMA LENGKAP SISWA</th>
                                <th class="p-3 border border-gray-300 font-semibold w-32 text-center">NO HP</th>
                                <th class="p-3 border border-gray-300 font-semibold w-24 text-center">KELAS</th>
                                <th class="p-3 border border-gray-300 font-semibold">MOTIVASI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $index => $row)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="p-3 border border-gray-200 text-center">{{ $index + 1 }}</td>
                                    <td class="p-3 border border-gray-200 font-medium">{{ $row->siswa->nama_lengkap ?? '-' }}</td>
                                    <td class="p-3 border border-gray-200 text-center">{{ $row->nomor_hp ?? '-' }}</td>
                                    <td class="p-3 border border-gray-200 text-center">{{ $row->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="p-3 border border-gray-200 text-sm">{{ $row->motivasi ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 border border-gray-200 text-center text-gray-500 italic">Belum ada anggota yang terdaftar di ekstrakurikuler ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        @elseif(request()->has('ekskul_id'))
            <div class="text-center p-8 bg-red-50 text-red-600 rounded-lg no-print">
                Ekstrakurikuler tidak ditemukan atau sudah dinonaktifkan.
            </div>
        @endif
        
    </div>

    <style>
        /* CSS tambahan untuk menyembunyikan shadow box di layar putih murni saat diprint */
        @media print {
            .no-print-wrapper { box-shadow: none !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</body>
</html>
