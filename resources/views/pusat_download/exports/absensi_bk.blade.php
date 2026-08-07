<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir BK - {{ $kelas->nama_kelas }}</title>
    <style>
        @page { margin: 0.4cm; size: 13in 8.5in landscape; }
        body { font-family: Arial, sans-serif; font-size: 8.5px; }
        
        .info-table { width: 100%; margin-bottom: 6px; font-weight: bold; font-size: 9px; }
        .info-table td { padding: 1px 2px; }
        
        .data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 2px 1px; height: 13px; overflow: hidden; font-size: 8px; }
        .data-table th { background-color: #f0f0f0; text-align: center; font-size: 7.5px; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; padding-left: 4px !important; }
        
        .nama-siswa { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 8.5px; }
        
        .footer { margin-top: 15px; width: 100%; font-size: 9.5px; }
        .clearfix::after { content: ""; clear: both; display: table; }

        .page-break { page-break-before: always; }

        /* Menyembunyikan tombol saat dicetak */
        @media print { .btn-print { display: none !important; } }
    </style>
</head>
<body>

    <!-- Tombol Bantuan Cetak -->
    <div class="btn-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #6d28d9; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            🖨️ Cetak / Simpan PDF (BK - Lanskap 31 Hari)
        </button>
    </div>

    @php
        $logoSetting = \DB::table('pengaturan_logo')->first();
        $logoPemda   = $logoSetting && $logoSetting->logo_pemda   ? asset('storage/' . $logoSetting->logo_pemda)   : null;
        $logoSekolah = $logoSetting && $logoSetting->logo_sekolah ? asset('storage/' . $logoSetting->logo_sekolah) : null;

        // Pecah data siswa menjadi chunk per halaman (33 baris per halaman)
        $perHalaman = 33;
        $chunks = $anggota->chunk($perHalaman);
        $nomorUrut = 1;
    @endphp

    @foreach($chunks as $halamanIndex => $chunk)
        {{-- Halaman ke-2 dst diberi page-break --}}
        @if($halamanIndex > 0)
            <div class="page-break"></div>
        @endif

        {{-- ============================================
             HEADER KOP SURAT (Muncul di setiap halaman)
             ============================================ --}}
        <table style="width: 100%; border-bottom: 2px solid #000; margin-bottom: 8px; padding-bottom: 4px;">
            <tr>
                <td style="width: 10%; text-align: left; vertical-align: middle;">
                    @if($logoPemda)
                        <img src="{{ $logoPemda }}" style="max-height: 55px; max-width: 60px; object-fit: contain;">
                    @endif
                </td>
                <td style="width: 80%; text-align: center; vertical-align: middle;">
                    <h2 style="margin: 1px 0; font-size: 14px; text-transform: uppercase;">DAFTAR HADIR BULANAN SISWA (BIMBINGAN KONSELING)</h2>
                    <h3 style="margin: 1px 0; font-size: 12px; text-transform: uppercase;">{{ $nama_sekolah }}</h3>
                    <p style="margin: 1px 0; font-size: 10px;">Tahun Ajaran {{ $tahun_ajaran ?? '-' }}</p>
                </td>
                <td style="width: 10%; text-align: right; vertical-align: middle;">
                    @if($logoSekolah)
                        <img src="{{ $logoSekolah }}" style="max-height: 55px; max-width: 60px; object-fit: contain;">
                    @endif
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="10%">Kelas / Rombel</td>
                <td width="30%">: {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})</td>
                <td width="10%">Bulan / Tahun</td>
                <td width="50%">: ............................................................ {{ date('Y') }}</td>
            </tr>
            <tr>
                <td>Wali Kelas</td>
                <td>: {{ $kelas->waliKelas ? $kelas->waliKelas->nama_lengkap : '-' }}</td>
                <td>Jumlah Siswa</td>
                <td>: {{ $anggota->count() }} (Laki-laki: {{ $laki_laki }}, Perempuan: {{ $perempuan }})</td>
            </tr>
            <tr>
                <td>Guru BK</td>
                <td>: {{ $guruBk->pegawai ? $guruBk->pegawai->nama_lengkap : '-' }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        {{-- ============================================
             TABEL DATA SISWA
             ============================================ --}}
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 2.2%;">No</th>
                    <th rowspan="2" style="width: 7.5%;">NISN</th>
                    <th rowspan="2" style="width: 18%;">Nama Lengkap Siswa</th>
                    <th rowspan="2" style="width: 2.2%;">L/P</th>
                    <th rowspan="2" style="width: 3.5%;">Gaya<br>Belajar</th>
                    <th colspan="31">Tanggal ( 1 s.d 31 )</th>
                    <th colspan="4">Rekapitulasi</th>
                </tr>
                <tr>
                    @for($d = 1; $d <= 31; $d++)
                        <th style="width: 1.8%;">{{ $d }}</th>
                    @endfor
                    <th style="width: 1.8%; background-color: #fee2e2;">S</th>
                    <th style="width: 1.8%; background-color: #fef3c7;">I</th>
                    <th style="width: 1.8%; background-color: #fce7f3;">A</th>
                    <th style="width: 1.8%; background-color: #e0e7ff;">T</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chunk as $item)
                    <tr>
                        <td class="text-center">{{ $nomorUrut++ }}</td>
                        <td class="text-center">{{ $item->siswa->nisn ?? '-' }}</td>
                        <td class="text-left nama-siswa">{{ $item->siswa->nama_lengkap }}</td>
                        <td class="text-center">{{ in_array($item->siswa->jenis_kelamin, ['Laki-Laki','Laki-laki','L']) ? 'L' : 'P' }}</td>
                        <td class="text-center" style="font-size: 7px; font-weight: bold;">
                            @php 
                                $gb = $item->siswa->hasilGayaBelajar->gaya_dominan ?? '-';
                                if ($gb != '-') { $gb = strtoupper(substr($gb, 0, 1)); }
                            @endphp
                            {{ $gb }}
                        </td>
                        
                        {{-- 31 Kolom Tanggal Kosong untuk Diisi Manual --}}
                        @for($d = 1; $d <= 31; $d++)
                            <td></td>
                        @endfor
                        
                        {{-- Kolom Rekap S, I, A, T --}}
                        <td style="background-color: #fff5f5;"></td>
                        <td style="background-color: #fffbeb;"></td>
                        <td style="background-color: #fff1f2;"></td>
                        <td style="background-color: #f0fdf4;"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="41" class="text-center" style="padding: 12px;">Belum ada data anggota kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer hanya di halaman TERAKHIR --}}
        @if($loop->last)
            <div class="footer clearfix">
                <div style="float: left; width: 45%; font-size: 8.5px; margin-top: 5px;">
                    <p style="margin: 1px 0;"><strong>Keterangan Gaya Belajar:</strong> <strong>V</strong> = Visual, <strong>A</strong> = Auditory, <strong>K</strong> = Kinesthetic</p>
                    <p style="margin: 1px 0;"><strong>Keterangan Presensi:</strong> <strong>S</strong> = Sakit, <strong>I</strong> = Izin, <strong>A</strong> = Alpa, <strong>T</strong> = Terlambat</p>
                </div>
                
                {{-- Area TTD: Guru BK (kiri) & Wali Kelas (kanan) --}}
                <div style="float: right; width: 55%; display: flex; justify-content: space-around;">
                    
                    {{-- TTD Guru BK --}}
                    <div style="text-align: center; font-size: 9.5px; width: 45%;">
                        <p>Cibitung, .................................. {{ date('Y') }}</p>
                        <p>Guru BK,</p>
                        <br><br><br>
                        <p><strong><u>{{ $guruBk->pegawai ? $guruBk->pegawai->nama_lengkap : '.....................................' }}</u></strong></p>
                        <p>NIP. {{ $guruBk->pegawai ? ($guruBk->pegawai->nip ?? '............................') : '............................' }}</p>
                    </div>

                    {{-- TTD Wali Kelas --}}
                    <div style="text-align: center; font-size: 9.5px; width: 45%;">
                        <p>Cibitung, .................................. {{ date('Y') }}</p>
                        <p>Wali Kelas {{ $kelas->nama_kelas }},</p>
                        <br><br><br>
                        <p><strong><u>{{ $kelas->waliKelas ? $kelas->waliKelas->nama_lengkap : '.....................................' }}</u></strong></p>
                        <p>NIP. {{ $kelas->waliKelas ? ($kelas->waliKelas->nip ?? '............................') : '............................' }}</p>
                    </div>
                </div>
            </div>
        @endif

    @endforeach

</body>
</html>
