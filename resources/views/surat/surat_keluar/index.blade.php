<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Agenda Surat Keluar') }}</h2>
    </x-slot>

    {{-- Quill Rich Text Editor CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <style>
        .ql-editor table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        .ql-editor table:not([border="1"]) td, 
        .ql-editor table:not([border="1"]) th { border: none !important; padding: 2px 0; }
        .ql-editor table[border="1"] td, 
        .ql-editor table[border="1"] th { border: 1px solid #000 !important; padding: 4px 6px; }
    </style>

    <script>
        function suratKeluarApp() {
            return {
                openCreate: false,
                quill: null,
                daftarPegawai: @json($daftarPegawai),
                daftarSiswa: @json($daftarSiswa),
                selectedPegawaiId: '',
                selectedSiswaId: '',
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
                            if (this.$refs.hiddenIsiSurat) {
                                this.$refs.hiddenIsiSurat.value = this.quill.root.innerHTML;
                            }
                        });
                    }
                },
                insertTemplate(type) {
                    let html = '';
                    if (type === 'sk') {
                        html = '<p style="text-align: center;"><strong>SURAT KEPUTUSAN</strong><br><strong>KEPALA SMP NEGERI 4 CIBITUNG KABUPATEN BEKASI</strong><br>NOMOR : [nomor]/[kode]/SMPN4Cbt/[bulan]/[tahun]</p><p style="text-align: center;">TENTANG<br><strong>PEMBAGIAN TUGAS DAN BEBAN KERJA PENDIDIK DAN TENAGA KEPENDIDIKAN<br>DALAM PROSES PENGALAMAN BELAJAR MURID<br>TAHUN AJARAN 2026 - 2027</strong></p><br><table style="width: 100%; border-collapse: collapse;"><tr><td style="width: 15%; vertical-align: top; font-weight: bold;">Menimbang</td><td style="width: 3%; vertical-align: top; font-weight: bold;">:</td><td style="width: 82%; vertical-align: top; text-align: justify;"><ol type="a" style="margin:0; padding-left: 15px;"><li>Bahwa proses pengalaman belajar murid dengan pendekatan pembelajaran mendalam merupakan inti proses penyelenggaraan pendidikan pada satuan pendidikan untuk mencapai delapan dimensi profil lulusan;</li><li>Bahwa berdasarkan poin a untuk menjamin kelancaran proses pengalaman belajar murid perlu ditetapkan pembagian beban mengajar pendidik/guru dan tugas tambahan bagi Pendidik SMPN 4 Cibitung Tahun Ajaran 2026-2027;</li><li>Bahwa untuk memberi layanan administrasi dalam proses pengalaman belajar yang dilaksanakan pendidik/guru sesuai poin b, perlu pula ditetapkan penetapan tugas kerja bagi Tenaga Kependidikan SMPN 4 Cibitung Tahun Ajaran 2026-2027.</li></ol></td></tr><tr><td style="vertical-align: top; font-weight: bold; padding-top: 10px;">Mengingat</td><td style="vertical-align: top; font-weight: bold; padding-top: 10px;">:</td><td style="vertical-align: top; text-align: justify; padding-top: 10px;"><ol type="a" style="margin:0; padding-left: 15px;"><li>Undang-undang Republik Indonesia Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</li><li>Undang-undang Republik Indonesia Nomor 14 Tahun 2005 Tentang Guru dan Dosen;</li><li>Peraturan Pemerintah Republik Indonesia Nomor 57 Tahun 2021 tentang Standar Nasional Pendidikan;</li><li>Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Nomor 32 Tahun 2022 tentang Standar Pelayanan Minimal Pendidikan;</li><li>Peraturan Menteri Pendidikan Dasar dan Menengah Nomor 11 Tahun 2025 tentang Pemenuhan Beban Kerja Guru.</li></ol></td></tr></table><p style="text-align: center; margin-top: 20px;"><strong>MEMUTUSKAN</strong></p><table style="width: 100%; border-collapse: collapse;"><tr><td style="width: 15%; vertical-align: top; font-weight: bold;">Menetapkan</td><td style="width: 3%; vertical-align: top; font-weight: bold;">:</td><td style="width: 82%; vertical-align: top; text-align: justify;"><strong>Keputusan Kepala SMP Negeri 4 Cibitung Kecamatan Cibitung Kabupaten Bekasi tentang Pembagian Tugas dan Beban Mengajar Pendidik dan Tenaga Kependidikan Dalam Proses Pengalaman Belajar Murid Tahun Ajaran 2026-2027</strong></td></tr><tr><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">Pertama</td><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">:</td><td style="vertical-align: top; text-align: justify; padding-top: 8px;">Beban kerja dan mengajar Pendidik dan Tenaga Kependidikan tahun ajaran 2026-2027 meliputi kewajiban tatap muka/mengajar pembelajaran intrakurikuler dan kokurikuler, kegiatan bimbingan akademik guru wali, Layanan Konseling bagi Guru BK, pembinaan ekstrakurikuler dan tugas tambahan lainnya.</td></tr><tr><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">Kedua</td><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">:</td><td style="vertical-align: top; text-align: justify; padding-top: 8px;">Beban kerja dan mengajar Pendidik dan Tenaga Kependidikan dalam proses Pengalaman Belajar Murid tersebut tertuang dalam daftar terlampir.</td></tr><tr><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">Ketiga</td><td style="vertical-align: top; font-weight: bold; padding-top: 8px;">:</td><td style="vertical-align: top; text-align: justify; padding-top: 8px;">Apabila dikemudian hari ternyata terdapat kekeliruan dalam keputusan ini akan dilakukan perbaikan dan perhitungan kembali sebagaimana mestinya.</td></tr></table><br><div style="margin-top: 15px;"><p><strong>Tembusan Yth :</strong><br>1. Kepala Dinas Pendidikan Kabupaten Bekasi<br>2. Pengawas/Pembina Sekolah<br>3. Ketua Komite Sekolah<br>4. Arsip</p></div>';
                    } else if (type === 'sppd') {
                        html = '<p style="text-align: center;"><u><strong>SURAT TUGAS</strong></u><br>Nomor : 000.4/[nomor]/SMPN4Cbt/Disdik/[bulan]/[tahun]</p><br><table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;"><tr><td style="width: 25%; padding: 2px 0;">Nama ( yang memberi tugas )</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 72%; padding: 2px 0;">Siti Nurchayati, M.Pd</td></tr><tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Kepala Sekolah</td></tr></table><p style="text-align: center; margin-top: 15px; margin-bottom: 15px;"><strong>MENUGASKAN :</strong></p><p><strong>Kepada :</strong></p><table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-bottom: 15px;"><tr><td style="width: 20%; padding: 2px 0;">Nama</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 77%; padding: 2px 0;"><strong>[Nama Pegawai]</strong></td></tr><tr><td style="padding: 2px 0;">NIP</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NIP Pegawai]</td></tr><tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[Jabatan Pegawai]</td></tr></table><p style="text-align: justify;">Untuk : [Nama Maksud Kegiatan Perjalanan Dinas] yang akan dilaksanakan pada :</p><table style="width: 100%; border-collapse: collapse; margin-left: 20px; margin-top: 5px; margin-bottom: 15px;"><tr><td style="width: 20%; padding: 2px 0;">Hari</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 77%; padding: 2px 0;">[Hari Kegiatan]</td></tr><tr><td style="padding: 2px 0;">Tanggal</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[Tanggal Berangkat] s/d [Tanggal Kembali]</td></tr><tr><td style="padding: 2px 0;">Waktu</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">08.00 s/d Selesai</td></tr><tr><td style="padding: 2px 0; vertical-align: top;">Tempat</td><td style="padding: 2px 0; vertical-align: top;">:</td><td style="padding: 2px 0; vertical-align: top;">[Lokasi Tempat Tujuan]</td></tr></table><p style="text-align: justify;">Demikianlah surat tugas ini dibuat untuk dapat dipergunakan sebaik - baiknya dan dilaksanakan dengan penuh rasa tanggung jawab serta menyampaikan laporan apabila telah selesai melaksanakan.</p><div style="page-break-before: always; margin-top: 30px;"></div><table style="width: 100%; font-size: 10px; margin-bottom: 10px;"><tr><td style="width: 70%;"></td><td style="width: 30%;">Lembar :<br>Kode No :<br>Nomor : 000.4/[nomor]/SMPN4Cbt/Disdik/[bulan]/[tahun]</td></tr></table><p style="text-align: center; font-size: 14px;"><strong>SURAT PERINTAH PERJALANAN DINAS</strong></p><table border="1" style="width: 100%; border-collapse: collapse; font-size: 11px;"><tr><td style="width: 5%; text-align: center; padding: 4px;">1</td><td style="width: 45%; padding: 4px;">Pejabat berwenang yang memberi perintah</td><td style="width: 50%; padding: 4px;">Siti Nurchayati, M.Pd</td></tr><tr><td style="text-align: center; padding: 4px;">2</td><td style="padding: 4px;">Nama Pegawai yang diperintahkan</td><td style="padding: 4px;"><strong>[Nama Pegawai]</strong></td></tr><tr><td style="text-align: center; padding: 4px; vertical-align: top;">3</td><td style="padding: 4px;">a. Pangkat dan golongan Gaji menurut Peraturan Pemerintah No. 6 Tahun 1997<br>b. Jabatan / Instansi<br>c. Tingkat menurut Peraturan Perjalanan Dinas</td><td style="padding: 4px;">a. [Pangkat Pegawai]<br>b. [Jabatan Pegawai] / SMPN 4 Cibitung<br>c. Perjalanan Dinas Dalam Daerah</td></tr><tr><td style="text-align: center; padding: 4px;">4</td><td style="padding: 4px;">Maksud Perjalanan Dinas</td><td style="padding: 4px;"><strong>[Nama Maksud Kegiatan Perjalanan Dinas]</strong></td></tr><tr><td style="text-align: center; padding: 4px;">5</td><td style="padding: 4px;">Alat Angkutan yang dipergunakan</td><td style="padding: 4px;">Kendaraan umum</td></tr><tr><td style="text-align: center; padding: 4px; vertical-align: top;">6</td><td style="padding: 4px;">a. Tempat berangkat<br>b. Tempat tujuan</td><td style="padding: 4px;">a. SMPN 4 Cibitung<br>b. [Lokasi Tempat Tujuan]</td></tr><tr><td style="text-align: center; padding: 4px; vertical-align: top;">7</td><td style="padding: 4px;">a. Lamanya perjalanan dinas<br>b. Tanggal Berangkat<br>c. Tanggal harus kembali/ Tiba di tempat baru*)</td><td style="padding: 4px;">a. 1 (Satu) Hari<br>b. [Tanggal Berangkat]<br>c. [Tanggal Kembali]</td></tr><tr><td style="text-align: center; padding: 4px; vertical-align: top;">8</td><td style="padding: 4px;">Pengikut : Nama | Tanggal Lahir | Keterangan</td><td style="padding: 4px;">-</td></tr><tr><td style="text-align: center; padding: 4px; vertical-align: top;">9</td><td style="padding: 4px;">Pembebanan Anggaran<br>a. Instansi<br>b. Mata Anggaran</td><td style="padding: 4px;"><br>a. SMPN 4 Cibitung<br>b. DPA/BOS</td></tr><tr><td style="text-align: center; padding: 4px;">10</td><td style="padding: 4px;">Keterangan lain-lain</td><td style="padding: 4px;"></td></tr></table><div style="page-break-before: always; margin-top: 30px;"></div><table border="1" style="width: 100%; border-collapse: collapse; font-size: 10px;"><tr><td style="width: 50%; padding: 6px;"></td><td style="width: 50%; padding: 6px;">SPPD No : 000.4/[nomor]/SMPN4Cbt/Disdik/[bulan]/[tahun]<br>1. Berangkat dari : SMPN 4 Cibitung<br>&nbsp;&nbsp;&nbsp;(Tempat Kedudukan)<br>&nbsp;&nbsp;&nbsp;Ke : [Lokasi Tempat Tujuan]<br>&nbsp;&nbsp;&nbsp;Pada tanggal : [Tanggal Berangkat]<br><br><br><strong><u>Siti Nurchayati, M.Pd</u></strong><br>Pembina Utama Muda, IV/c</td></tr><tr><td style="padding: 6px;">II. Tiba di : [Lokasi Tempat Tujuan]<br>&nbsp;&nbsp;&nbsp;&nbsp;Pada Tanggal : [Tanggal Berangkat]<br>&nbsp;&nbsp;&nbsp;&nbsp;Kepala<br><br><br><br>...........................................................<br>NIP.</td><td style="padding: 6px;">Berangkat dari : SMPN 4 Cibitung<br>( Tempat Kedudukan )<br>Ke : [Lokasi Tempat Tujuan]<br>Pada tanggal : [Tanggal Berangkat]<br><br><br><br>...........................................................<br>NIP.</td></tr><tr><td style="padding: 6px;">III. Tiba di : Depo Arsip Kabupaten Bekasi<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pada Tanggal : [Tanggal Kembali]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepala<br><br><br><br>...........................................................<br>NIP.</td><td style="padding: 6px;">1. Berangkat dari : Depo Arsip Kabupaten Bekasi<br>&nbsp;&nbsp;&nbsp;( Tempat Kedudukan )<br>&nbsp;&nbsp;&nbsp;Ke : SMPN 4 Cibitung<br>&nbsp;&nbsp;&nbsp;Pada tanggal : [Tanggal Kembali]<br><br><br><br>...........................................................<br>NIP.</td></tr></table>';
                    } else if (type === 'keterangan') {
                        html = '<p style="text-align: center;"><u><strong>SURAT KETERANGAN</strong></u><br>Nomor : 400.3.5.6/ [nomor] /SMPN4Cbt/Disdik/[bulan]/[tahun]</p><br><p style="text-align: justify;">Yang bertanda tangan di bawah ini, Kepala SMP Negeri 4 Cibitung Kabupaten Bekasi dengan ini menerangkan :</p><table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px;"><tr><td style="width: 30%; padding: 3px 0;">Nama Siswa</td><td style="width: 3%; padding: 3px 0;">:</td><td style="width: 67%; padding: 3px 0;"><strong>[Nama Siswa]</strong></td></tr><tr><td style="padding: 3px 0;">Tempat, Tanggal Lahir</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Tempat, Tanggal Lahir]</td></tr><tr><td style="padding: 3px 0;">Nomor Induk / NISN</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[NIPD] / [NISN Siswa]</td></tr><tr><td style="padding: 3px 0;">Jenis Kelamin</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Jenis Kelamin]</td></tr><tr><td style="padding: 3px 0;">Nama Ayah</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Nama Ayah]</td></tr><tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Pekerjaan Ayah]</td></tr><tr><td style="padding: 3px 0;">Nama Ibu</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Nama Ibu]</td></tr><tr><td style="padding: 3px 0;">Pekerjaan</td><td style="padding: 3px 0;">:</td><td style="padding: 3px 0;">[Pekerjaan Ibu]</td></tr><tr><td style="padding: 3px 0; vertical-align: top;">Alamat</td><td style="padding: 3px 0; vertical-align: top;">:</td><td style="padding: 3px 0; vertical-align: top;">[Alamat Lengkap]</td></tr></table><p style="text-align: justify;">Adalah benar siswa kelas [Kelas Siswa] pada SMP Negeri 4 Cibitung Kabupaten Bekasi dan masih aktif bersekolah.</p><p style="text-align: justify;">Demikian surat keterangan kami buat agar dapat dipergunakan sebagaimana mestinya.</p>';
                    } else if (type === 'skhun') {
                        html = '<p style="text-align: center;"><u><strong>SURAT KETERANGAN</strong></u><br>Nomor : 400.3.5.1/[nomor]/SMPN4Cbt/Disdik/[bulan]/[tahun]</p><br><p>Yang bertandatangan di bawah ini :</p><table style="width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px;"><tr><td style="width: 25%; padding: 2px 0;">Nama</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 72%; padding: 2px 0;">SITI NURCHAYATI, M.Pd</td></tr><tr><td style="padding: 2px 0;">NIP</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">197307152000032007</td></tr><tr><td style="padding: 2px 0;">Pangkat/ Gol. Ruang</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Pembina Utama Muda / IV.c</td></tr><tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Kepala Sekolah</td></tr><tr><td style="padding: 2px 0;">Unit Kerja</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">SMP Negeri 4 Cibitung</td></tr></table><p style="text-align: justify;">Menerangkan bahwa pada Tahun Pelajaran 2021/2022, Pemerintah tidak menerbitkan Surat Keterangan Hasil Ujian Nasional (SKHUN). Yang diterbitkan Pemerintah hanya <u>IJAZAH</u>, dan pihak sekolah hanya menerbitkan Surat Keterangan Kelulusan (SKL). Oleh sebab itu kami memberikan surat keterangan atas nama :</p><table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;"><tr><td style="width: 30%; padding: 2px 0;">Nama</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 67%; padding: 2px 0;"><strong>[Nama Siswa]</strong></td></tr><tr><td style="padding: 2px 0;">Tempat dan Tanggal Lahir</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[Tempat, Tanggal Lahir]</td></tr><tr><td style="padding: 2px 0;">Lulusan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Tahun Pelajaran 2021/2022</td></tr></table><p style="text-align: justify;">Surat Keterangan ini dibuat sebagai bahan untuk melengkapi administrasi [Tujuan Keperluan, Contoh: Pendaftaran Calon Bintara Kepolisian Gelombang II TA 2026].</p><p style="text-align: justify;">Demikian Surat Keterangan ini dibuat dengan sesungguhnya dan sebenar-benarnya untuk digunakan sebagaimana mestinya.</p>';
                    } else if (type === 'skmt') {
                        html = '<p style="text-align: center;"><strong>SURAT KETERANGAN MENJALANKAN TUGAS (SKMT)</strong><br><strong>PEMBELAJARAN/PROGRAM GURU AGAMA ISLAM/BIMBINGAN DAN TUGAS TERTENTU</strong><br>Nomor : [nomor]/[kode]/SMPN4Cbt/[bulan]/[tahun]</p><br><p><strong>Yang bertanda tangan di bawah ini:</strong></p><table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;"><tr><td style="width: 20%; padding: 2px 0;">Nama</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 77%; padding: 2px 0;">Siti Nurchayati, M.Pd</td></tr><tr><td style="padding: 2px 0;">NIP</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">197307152000032007</td></tr><tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">Kepala Sekolah</td></tr></table><p><strong>Menyatakan Bahwa:</strong></p><table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;"><tr><td style="width: 20%; padding: 2px 0;">Nama</td><td style="width: 3%; padding: 2px 0;">:</td><td style="width: 77%; padding: 2px 0;"><strong>[Nama Pegawai]</strong></td></tr><tr><td style="padding: 2px 0;">NIP</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NIP Pegawai]</td></tr><tr><td style="padding: 2px 0;">NUPTK</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[NUPTK Pegawai]</td></tr><tr><td style="padding: 2px 0;">Jabatan</td><td style="padding: 2px 0;">:</td><td style="padding: 2px 0;">[Jabatan Pegawai]</td></tr></table><p style="text-align: justify;">Telah melaksanakan kegiatan pembelajaran/program pengembangan GURU AGAMA ISLAM/bimbingan dan tugas tertentu dengan rincian sebagai berikut:</p><table border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; font-size: 11px;"><thead><tr style="background-color: #f3f4f6;"><th style="width: 8%; padding: 6px; text-align: center;">NO</th><th style="width: 72%; padding: 6px; text-align: center;">URAIAN</th><th style="width: 20%; padding: 6px; text-align: center;">HASIL PENILAIAN KINERJA</th></tr></thead><tbody><tr><td style="text-align: center; vertical-align: top; padding: 6px;">A.</td><td style="padding: 6px; text-align: justify;"><strong>Melaksanakan Proses Pembelajaran/Program Pengembangan GURU AGAMA ISLAM</strong><br>Merencanakan dan melaksanakan, mengevaluasi dan menilai hasil, menganalisis hasil, melaksanakan tindak lanjut hasil penilaian. (Tahun Ajaran 2026/2027 Semester Ganjil)</td><td style="text-align: center; vertical-align: middle; padding: 6px;">Baik</td></tr><tr><td style="text-align: center; vertical-align: top; padding: 6px;">B.</td><td style="padding: 6px; text-align: justify;"><strong>Melaksanakan Proses Bimbingan</strong><br>Merencanakan dan melaksanakan bimbingan, menganalisis hasil bimbingan, dan melaksanakan tindak lanjut hasil bimbingan.</td><td style="text-align: center; vertical-align: middle; padding: 6px;">Baik</td></tr><tr><td style="text-align: center; vertical-align: top; padding: 6px;">C.</td><td style="padding: 6px;"><strong>Perhitungan JTM</strong></td><td style="padding: 6px;"></td></tr><tr><td></td><td style="padding: 4px 6px 4px 20px;">JTM Reguler</td><td style="text-align: center; padding: 4px 6px;">36</td></tr><tr><td></td><td style="padding: 4px 6px 4px 20px;">JTM Tambahan</td><td style="text-align: center; padding: 4px 6px;">4</td></tr></tbody></table><p style="text-align: justify;">Demikian pernyataan ini dibuat untuk dipergunakan sebagaimana mestinya.</p><br><table style="width: 100%; border-collapse: collapse; margin-top: 15px;"><tr><td style="width: 50%; vertical-align: top;"><p>Mengetahui,<br>Pengawas GURU AGAMA ISLAM</p><br><br><br><p><strong><u>YUNI ASDHIANI M.PD</u></strong><br>NIP. 198006052003122002</p></td><td style="width: 50%; vertical-align: top; text-align: left;"><p>Kabupaten Bekasi, 21 Juli 2026<br>Kepala Sekolah,<br>SMPN 4 CIBITUNG</p><br><br><br><p><strong><u>SITI NURCHAYATI, M.PD</u></strong><br>NIP. 197307152000032007</p></td></tr></table>';
                    } else if (type === 'undangan') {
                        html = '<p>Kepada Yth.<br><strong>Bapak/Ibu [Tujuan Undangan]</strong><br>di Tempat</p><p>Dengan hormat,</p><p>Sehubungan dengan agenda kegiatan sekolah, kami mengundang Bapak/Ibu untuk hadir pada acara yang akan dilaksanakan pada:</p><p><strong>Hari / Tanggal:</strong> [Hari, Tanggal]<br><strong>Waktu:</strong> [Waktu WIB]<br><strong>Tempat:</strong> [Lokasi Rapat]<br><strong>Agenda:</strong> [Topik Rapat]</p><p>Demikian surat undangan ini kami sampaikan. Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>';
                    }
                    if (this.quill) {
                        let qlEditor = this.$refs.editorContainer ? this.$refs.editorContainer.querySelector('.ql-editor') : null;
                        if (qlEditor) {
                            qlEditor.innerHTML = html;
                        } else {
                            this.quill.clipboard.dangerouslyPasteHTML(html);
                        }
                        if (this.$refs.hiddenIsiSurat) {
                            this.$refs.hiddenIsiSurat.value = qlEditor ? qlEditor.innerHTML : this.quill.root.innerHTML;
                        }
                    }
                },
                insertSelectedPegawai() {
                    if (!this.selectedPegawaiId) return;
                    let p = this.daftarPegawai.find(x => x.id == this.selectedPegawaiId);
                    if (!p) return;

                    let qlEditor = this.$refs.editorContainer ? this.$refs.editorContainer.querySelector('.ql-editor') : null;
                    let htmlContent = qlEditor ? qlEditor.innerHTML : (this.quill ? this.quill.root.innerHTML : '');
                    let jabatanNama = p.jabatan || p.jenis_ptk || '-';

                    if (htmlContent.includes('[Nama Pegawai]') || htmlContent.includes('[NIP Pegawai]')) {
                        htmlContent = htmlContent
                            .replace(/\[Nama Pegawai\]/g, p.nama_lengkap || '-')
                            .replace(/\[NIP Pegawai\]/g, p.nip || '-')
                            .replace(/\[NUPTK Pegawai\]/g, p.nuptk || '-')
                            .replace(/\[Pangkat Pegawai\]/g, p.pangkat_golongan || '-')
                            .replace(/\[Jabatan Pegawai\]/g, jabatanNama);
                    } else {
                        htmlContent += `<p><strong>Nama Pegawai:</strong> ${p.nama_lengkap || '-'}<br><strong>NIP:</strong> ${p.nip || '-'}<br><strong>Jabatan:</strong> ${jabatanNama}</p>`;
                    }

                    if (qlEditor) {
                        qlEditor.innerHTML = htmlContent;
                    } else if (this.quill) {
                        this.quill.clipboard.dangerouslyPasteHTML(htmlContent);
                    }
                    if (this.$refs.hiddenIsiSurat) {
                        this.$refs.hiddenIsiSurat.value = htmlContent;
                    }
                },
                insertSelectedSiswa() {
                    if (!this.selectedSiswaId) return;
                    let s = this.daftarSiswa.find(x => x.id == this.selectedSiswaId);
                    if (!s) return;

                    let qlEditor = this.$refs.editorContainer ? this.$refs.editorContainer.querySelector('.ql-editor') : null;
                    let htmlContent = qlEditor ? qlEditor.innerHTML : (this.quill ? this.quill.root.innerHTML : '');

                    let namaKelas = s.kelas ? s.kelas.nama_kelas : 'Kelas Belum Set';
                    let tempatTglLahir = (s.tempat_lahir ? s.tempat_lahir + ', ' : '') + (s.tanggal_lahir ? new Date(s.tanggal_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-');
                    let nipdNisn = (s.nipd || '-') + ' / ' + (s.nisn || '-');
                    
                    let waliAyah = s.wali ? s.wali.find(w => w.pivot && (w.pivot.hubungan === 'Ayah' || w.pivot.hubungan === 'Orang Tua')) : null;
                    let waliIbu = s.wali ? s.wali.find(w => w.pivot && w.pivot.hubungan === 'Ibu') : null;

                    let namaAyah = waliAyah ? waliAyah.nama_lengkap : '-';
                    let pekerjaanAyah = waliAyah ? (waliAyah.pekerjaan || '-') : '-';
                    let namaIbu = waliIbu ? waliIbu.nama_lengkap : '-';
                    let pekerjaanIbu = waliIbu ? (waliIbu.pekerjaan || '-') : '-';

                    let alamatLengkap = s.alamat_lengkap || '';
                    if (s.rt || s.rw) alamatLengkap += (alamatLengkap ? ', ' : '') + 'RT.' + (s.rt || '-') + '/RW.' + (s.rw || '-');
                    if (s.kelurahan_desa) alamatLengkap += (alamatLengkap ? ' Desa/Kel. ' : '') + s.kelurahan_desa;
                    if (s.kecamatan) alamatLengkap += (alamatLengkap ? ' Kec. ' : '') + s.kecamatan;
                    if (!alamatLengkap) alamatLengkap = '-';

                    if (htmlContent.includes('[Nama Siswa]') || htmlContent.includes('[NISN Siswa]')) {
                        htmlContent = htmlContent
                            .replace(/\[Nama Siswa\]/g, s.nama_lengkap || '-')
                            .replace(/\[Tempat, Tanggal Lahir\]/g, tempatTglLahir)
                            .replace(/\[NIPD\] \/ \[NISN Siswa\]/g, nipdNisn)
                            .replace(/\[NISN Siswa\]/g, s.nisn || s.nipd || '-')
                            .replace(/\[Jenis Kelamin\]/g, s.jenis_kelamin || '-')
                            .replace(/\[Nama Ayah\]/g, namaAyah)
                            .replace(/\[Pekerjaan Ayah\]/g, pekerjaanAyah)
                            .replace(/\[Nama Ibu\]/g, namaIbu)
                            .replace(/\[Pekerjaan Ibu\]/g, pekerjaanIbu)
                            .replace(/\[Alamat Lengkap\]/g, alamatLengkap)
                            .replace(/\[Kelas Siswa\]/g, namaKelas);
                    } else {
                        htmlContent += `<p><strong>Nama Siswa:</strong> ${s.nama_lengkap || '-'}<br><strong>Tempat, Tanggal Lahir:</strong> ${tempatTglLahir}<br><strong>NISN / NIS:</strong> ${nipdNisn}<br><strong>Jenis Kelamin:</strong> ${s.jenis_kelamin || '-'}<br><strong>Nama Ayah:</strong> ${namaAyah}<br><strong>Nama Ibu:</strong> ${namaIbu}<br><strong>Alamat:</strong> ${alamatLengkap}<br><strong>Kelas:</strong> ${namaKelas}</p>`;
                    }

                    if (qlEditor) {
                        qlEditor.innerHTML = htmlContent;
                    } else if (this.quill) {
                        this.quill.clipboard.dangerouslyPasteHTML(htmlContent);
                    }
                    if (this.$refs.hiddenIsiSurat) {
                        this.$refs.hiddenIsiSurat.value = htmlContent;
                    }
                }
            };
        }
    </script>

    <div x-data="suratKeluarApp()" 
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

                    {{-- Sektor Asisten Pemilih Data Pegawai & Siswa --}}
                    <div class="p-3.5 bg-indigo-50/50 border border-indigo-100 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black text-indigo-900 uppercase flex items-center gap-1.5">
                                <span>🎯</span> Asisten Otomatisasi Data Pegawai & Siswa
                            </span>
                            <span class="text-[10px] font-bold text-indigo-500">Auto-Fill Nama, NIP, NISN & Kelas</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-1.5">
                                <select x-model="selectedPegawaiId" class="w-full text-xs font-semibold rounded-lg border-indigo-200 py-1.5 bg-white">
                                    <option value="">-- Pilih Pegawai/Guru --</option>
                                    <template x-for="p in daftarPegawai" :key="p.id">
                                        <option :value="p.id" x-text="p.nama_lengkap + ' (' + (p.nip || 'Tanpa NIP') + ')'"></option>
                                    </template>
                                </select>
                                <button type="button" @click="insertSelectedPegawai()" :disabled="!selectedPegawaiId" class="px-2.5 py-1.5 bg-sky-600 hover:bg-sky-700 disabled:bg-gray-300 text-white font-bold text-[11px] rounded-lg shrink-0 cursor-pointer shadow-sm transition-colors" title="Sisipkan data Pegawai terpilih ke isi surat">
                                    ➕ Sisipkan
                                </button>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <select x-model="selectedSiswaId" class="w-full text-xs font-semibold rounded-lg border-indigo-200 py-1.5 bg-white">
                                    <option value="">-- Pilih Siswa Aktif --</option>
                                    <template x-for="s in daftarSiswa" :key="s.id">
                                        <option :value="s.id" x-text="s.nama_lengkap + ' (' + (s.kelas ? s.kelas.nama_kelas : 'Tanpa Kelas') + ')'"></option>
                                    </template>
                                </select>
                                <button type="button" @click="insertSelectedSiswa()" :disabled="!selectedSiswaId" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 text-white font-bold text-[11px] rounded-lg shrink-0 cursor-pointer shadow-sm transition-colors" title="Sisipkan data Siswa terpilih ke isi surat">
                                    ➕ Sisipkan
                                </button>
                            </div>
                        </div>
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
                                <button type="button" @click="insertTemplate('skmt')" class="px-2 py-0.5 bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Surat Keterangan Menjalankan Tugas (SKMT) Guru">
                                    📝 SKMT Guru
                                </button>
                                <button type="button" @click="insertTemplate('keterangan')" class="px-2 py-0.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Surat Keterangan Siswa Aktif">
                                    🎓 Keterangan Siswa
                                </button>
                                <button type="button" @click="insertTemplate('skhun')" class="px-2 py-0.5 bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 rounded text-[10px] font-bold cursor-pointer transition-colors" title="Sisip Surat Keterangan Tidak Menerbitkan SKHUN / SKL">
                                    📄 Ket. SKHUN / SKL
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