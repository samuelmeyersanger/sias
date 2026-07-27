<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Ekskul - {{ $ekskul->nama }}</title>
    <style>
        /* Pengaturan Kertas Folio (F4) = 8.5 x 13 inci */
        @page { size: 8.5in 13in; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #000; }
        
        /* Kop Header */
        .kop-surat { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .teks-tengah { text-align: center; flex: 1; }
        .logo-sekolah { width: 80px; height: 80px; object-fit: contain; }
        .logo-ekskul { width: 70px; height: 70px; object-fit: contain; }
        
        /* Tabel Absensi */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; text-align: center; }
        
        /* Pengaturan Print Otomatis: Menyembunyikan tombol saat dicetak */
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <!-- Tombol Bantuan (Otomatis hilang saat halaman di-print) -->
    <div class="btn-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 18px; background: #dc2626; color: white; border: none; cursor: pointer; border-radius: 6px; font-weight: bold; font-size: 14px;">
            📄 Cetak / Simpan PDF
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <!-- Logo Ekskul (Kiri) -->
        @php
            $logoSetting = \DB::table('pengaturan_logo')->first();
            $logoPemda = $logoSetting && $logoSetting->logo_pemda ? asset('storage/' . $logoSetting->logo_pemda) : null;
            $logoSekolah = $logoSetting && $logoSetting->logo_sekolah ? asset('storage/' . $logoSetting->logo_sekolah) : null;
            $fallbackLogo = $logoPemda ?? $logoSekolah ?? 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEij-CjIeM5P4jF47L3K65mH5QG7Y1Q6Q7q6439m-K716-41L2Z74148K85V8v9P1N9l26m-O7H62n3M5q6F6k0O61o94K5j8P-9k9-O9q5Y5k3-93K9K-169m0Q33L37X6r0q5Y8-9/s1600/tut_wuri_handayani.png';
        @endphp

        @if($ekskul->logo_ekskul)
            <img src="{{ asset('storage/' . $ekskul->logo_ekskul) }}" class="logo-sekolah" style="object-fit: contain;" alt="Logo Ekskul">
        @else
            <img src="{{ $fallbackLogo }}" class="logo-sekolah" style="object-fit: contain; width: 80px; height: 80px;" alt="Logo Tut Wuri Handayani">
        @endif
        
        <div class="teks-tengah">
            <h2 style="margin: 0; font-size: 18px;">DAFTAR HADIR EKSTRAKURIKULER</h2>
            <h1 style="margin: 5px 0; font-size: 22px;">{{ strtoupper($ekskul->nama) }}</h1>
            <p style="margin: 0; font-size: 12px;">Semester: Ganjil / Genap &nbsp;&nbsp;|&nbsp;&nbsp; Tahun Ajaran: 2026/2027</p>
        </div>
        
        <!-- Kotak Kosong (Kanan) agar teks tetap pas di tengah -->
        <div style="width: 80px; height: 80px;"></div>
    </div>

    <!-- Informasi Pembina -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; font-size: 13px;">
        <div>Pembina : {{ $ekskul->pembina->nama_lengkap ?? '___________________________' }}</div>
        <div>Bulan : ___________________________</div>
    </div>

    <!-- Tabel Nama Otomatis -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2" style="width: 200px;">NAMA LENGKAP SISWA</th>
                <th rowspan="2" style="width: 100px;">NOMOR HP</th>
                <th rowspan="2" style="width: 70px;">KELAS</th>
                <!-- Disediakan 5 kolom pertemuan untuk absen -->
                <th colspan="5">TANGGAL PERTEMUAN</th>
            </tr>
            <tr>
                <th style="width: 45px; height: 25px;"></th>
                <th style="width: 45px;"></th>
                <th style="width: 45px;"></th>
                <th style="width: 45px;"></th>
                <th style="width: 45px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($ekskul->anggota as $index => $anggota)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ $anggota->siswa->nama_lengkap ?? '-' }}</td>
                <td style="text-align: center;">{{ $anggota->nomor_hp ?? '-' }}</td>
                <td style="text-align: center;">{{ $anggota->siswa->kelas->nama_kelas ?? '-' }}</td>
                <!-- Kolom Kosong untuk di-ceklis manual pakai pulpen -->
                <td></td><td></td><td></td><td></td><td></td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 30px; color: #666; font-style: italic;">
                    Belum ada anggota siswa yang terdaftar di Ekstrakurikuler ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pembatas agar tabel jurnal dan tanda tangan tidak terpotong (gantung) beda halaman -->
    <div style="page-break-inside: avoid; margin-top: 25px;">
        <!-- Tabel Materi / Kegiatan -->
        <div style="font-weight: bold; font-size: 13px; margin-bottom: 5px;">Jurnal Materi / Kegiatan Ekstrakurikuler</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">PERTEMUAN</th>
                    <th style="width: 120px;">TANGGAL</th>
                    <th>MATERI / KEGIATAN</th>
                    <th style="width: 150px;">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 5; $i++)
                <tr>
                    <td style="text-align: center; height: 30px;">Ke-{{ $i }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <!-- Tanda Tangan Pembina -->
        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <div style="width: 280px; text-align: center; font-size: 13px;">
                <p style="margin: 3px 0;">Cibitung, .................................... {{ date('Y') }}</p>
                <p style="margin: 3px 0;">Pembina Ekstrakurikuler,</p>
                <br><br><br><br>
                <p style="margin: 3px 0; font-weight: bold;">
                    <u>{{ $ekskul->pembina->nama_lengkap ?? '......................................................' }}</u>
                </p>
                <p style="margin: 3px 0;">
                    NIP. {{ $ekskul->pembina->nip ?? '....................................' }}
                </p>
            </div>
        </div>
    </div>

</body>
</html>