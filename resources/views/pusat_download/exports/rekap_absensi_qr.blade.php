<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Harian (QR Code)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .status-hadir { color: green; font-weight: bold; }
        .status-terlambat { color: orange; font-weight: bold; }
        .status-pulang { color: blue; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>REKAPITULASI ABSENSI HARIAN (SCAN QR CODE)</h2>
        <p><strong>{{ $nama_sekolah }}</strong></p>
        <p>Periode: {{ $tanggal_mulai }} s.d. {{ $tanggal_akhir }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">NISN</th>
                <th width="25%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="10%">Jam Masuk</th>
                <th width="10%">Jam Pulang</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensi as $index => $absen)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</td>
                    <td class="text-center">{{ $absen->siswa->nisn ?? '-' }}</td>
                    <td>{{ $absen->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</td>
                    <td class="text-center">{{ $absen->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="text-center">{{ $absen->waktu_masuk ?? '-' }}</td>
                    <td class="text-center">{{ $absen->waktu_pulang ?? '-' }}</td>
                    <td class="text-center">
                        @if($absen->status == 'Hadir')
                            <span class="status-hadir">{{ $absen->status }}</span>
                        @elseif($absen->status == 'Terlambat')
                            <span class="status-terlambat">{{ $absen->status }}</span>
                        @else
                            {{ $absen->status }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data absensi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
