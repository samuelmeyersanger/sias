<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            max-width: 21cm; /* A4 width */
            margin: 0 auto;
        }

        .card {
            width: 8.6cm;
            height: 5.4cm;
            background: linear-gradient(135deg, #cdecfc 0%, #ffffff 100%);
            border-radius: 8px;
            border: 1px solid #ccc;
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
            display: flex;
            page-break-inside: avoid;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        @media print {
            .card {
                box-shadow: none;
                border: 1px dashed #aaa;
            }
        }

        /* Left Side */
        .card-left {
            width: 35%;
            background: #a9dcf6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding-bottom: 15px;
            position: relative;
            border-right: 2px solid #82c8ea;
        }
        
        .card-left::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            opacity: 0.3;
        }

        .logo-container {
            position: absolute;
            top: 10px;
            width: 100%;
            text-align: center;
        }
        
        .logo-container img {
            width: 45px;
            height: auto;
        }

        .nisn-title {
            color: #0b4e82;
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 5px;
            text-shadow: 1px 1px 0px #fff;
        }

        .qr-box {
            background: #fff;
            padding: 3px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .qr-box img {
            width: 75px;
            height: 75px;
            display: block;
        }

        /* Right Side */
        .card-right {
            width: 65%;
            padding: 12px 10px 10px 15px;
            position: relative;
        }

        .header-text {
            text-align: right;
            margin-bottom: 10px;
            border-bottom: 1px solid #a9dcf6;
            padding-bottom: 4px;
        }

        .header-text h3 {
            margin: 0;
            color: #0b4e82;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.5px;
        }

        .header-text p {
            margin: 0;
            font-size: 6px;
            color: #0b4e82;
            font-weight: bold;
            text-transform: uppercase;
        }

        .biodata {
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }

        .biodata table {
            width: 100%;
            border-collapse: collapse;
        }

        .biodata td {
            vertical-align: top;
            padding: 1px 0;
        }

        .biodata td:first-child {
            width: 70px;
            font-weight: bold;
        }
        
        .biodata td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            width: 100px;
            z-index: 0;
            pointer-events: none;
        }
        
        .biodata table {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #0b4e82; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cetak Kartu</button>
    </div>

    @php
        $logoSetting = \DB::table('pengaturan_logo')->first();
        $logoSekolah = $logoSetting && $logoSetting->logo_sekolah ? asset('storage/' . $logoSetting->logo_sekolah) : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
    @endphp

    <div class="grid-container">
        @foreach($siswa as $s)
        <div class="card">
            <!-- Background Watermark Tut Wuri Handayani -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg" class="watermark" alt="Watermark">
            
            <div class="card-left">
                <div class="logo-container">
                    <!-- Ilustrasi generic seperti di gambar / logo sekolah -->
                    <img src="{{ $logoSekolah }}" alt="Logo">
                </div>
                
                <div class="nisn-title">NISN</div>
                
                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $s->nisn }}" alt="QR Code NISN">
                </div>
            </div>
            
            <div class="card-right">
                <div class="header-text">
                    <h3>KARTU PELAJAR & ABSENSI</h3>
                    <p>Data Pokok Pendidikan Indonesia</p>
                </div>
                
                <div class="biodata">
                    <table>
                        <tr>
                            <td>NIPD</td>
                            <td>:</td>
                            <td><strong>{{ $s->nipd ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>NISN</td>
                            <td>:</td>
                            <td><strong>{{ $s->nisn ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><strong>{{ strtoupper($s->nama_lengkap) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tempat Lahir</td>
                            <td>:</td>
                            <td>{{ strtoupper($s->tempat_lahir) }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
