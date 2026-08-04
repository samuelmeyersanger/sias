# 🏫 SIAS AKADEMIK — Sistem Informasi Akademik Sekolah

<div align="center">

**Platform manajemen sekolah all-in-one yang modern, lengkap, dan siap pakai.**

Dibangun dengan menggunakan Laravel 11 • Livewire • Alpine.js • Tailwind CSS

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-18-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://laravel.com/docs/sail)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📖 Tentang

**SIAS AKADEMIK** adalah sistem informasi akademik sekolah berbasis web yang dirancang untuk mendigitalisasi seluruh aspek operasional sekolah — mulai dari manajemen kesiswaan, kepegawaian, akademik, hingga persuratan dan bimbingan konseling.

Sistem ini berjalan di **PC Server lokal (On-Premise)** menggunakan **Docker**, sehingga dapat diakses oleh seluruh guru, staf, siswa, dan wali murid melalui jaringan Wi-Fi/LAN sekolah tanpa memerlukan internet.

### ✨ Keunggulan

- 🔒 **Role-Based Access Control** — 112 permission granular dengan middleware otomatis
- 📊 **Dashboard Multi-Role** — Tampilan khusus untuk Admin, Guru, Staf, dan Siswa
- 📥 **Import/Export Excel** — Import massal data siswa, pegawai, dan kelas dari Excel
- 📄 **Generate PDF** — Cetak absensi, jadwal, surat keluar dengan kop & TTD
- 💬 **Chat Internal** — Pesan pribadi & grup antar pengguna (teks, gambar, file, video)
- 🌏 **Data Wilayah Indonesia** — 80.000+ data provinsi hingga kelurahan terintegrasi
- 🔄 **Backup & Restore** — Backup database otomatis dengan Spatie Backup
- 🖥️ **On-Premise** — Berjalan di server lokal, data sekolah tetap aman

---

## 🚀 Fitur Lengkap (90+ Fitur)

<details>
<summary><b>🌐 Website Publik</b> — 6 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Landing Page | Halaman utama sekolah dengan profil, berita, dan halaman dinamis |
| Blog / Berita | Daftar artikel, detail, komentar publik |
| Halaman Dinamis (CMS) | Halaman statis yang dikelola admin (Visi Misi, Sejarah, dll) |
| Formulir Kontak | Form kontak untuk orang tua / masyarakat |
| Scanner QR Absensi | Scanner mandiri harian via barcode/QR + terintegrasi Aplikasi Android Hybrid (Native ZXing Scanner) |
| Info Ekstrakurikuler | Halaman publik daftar ekskul beserta detail pesertanya |

</details>

<details>
<summary><b>🔐 Autentikasi & Otorisasi</b> — 7 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Login / Register | Registrasi & login standar Laravel Breeze |
| Lupa & Reset Password | Alur reset password via email |
| Verifikasi Email | Verifikasi alamat email pengguna |
| Persetujuan Admin | User baru harus di-approve sebelum masuk sistem |
| Manajemen Role | Role-based access control dinamis |
| Manajemen Permission | 112 hak akses granular per modul |
| Middleware Otomatis | Cek hak akses otomatis berdasarkan nama route |

</details>

<details>
<summary><b>📊 Dashboard Multi-Role</b> — 5 tampilan</summary>

| Tampilan | Konten |
|----------|--------|
| Admin | Statistik total siswa, pegawai, kelas, ekskul + aktivitas terakhir |
| Guru | Jadwal mengajar hari ini, jumlah mapel, ekskul yang dibina |
| Staf TU | Statistik untuk staf tata usaha |
| Siswa | Informasi untuk siswa |
| Default | Fallback untuk role lainnya |

</details>

<details>
<summary><b>👨‍🎓 Modul Kesiswaan</b> — 15 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| CRUD Data Siswa | Tambah, ubah, hapus, lihat detail siswa lengkap |
| Import Siswa + Wali (Excel) | Import massal data siswa & wali murid dari Excel |
| Download Template Excel | Template import yang sudah terformat |
| Generate Akun Siswa | Buat akun login individu / massal |
| Manajemen Status | Aktif, Lulus, Pindah, Drop Out |
| Riwayat Kelas & Status | Histori perpindahan kelas dan perubahan status |
| Upload Dokumen Siswa | Upload berkas (akta, KK, ijazah, dll) |
| Prestasi Siswa | Catat prestasi akademik / non-akademik |
| Data Wali Murid | Data orang tua/wali terhubung ke siswa |
| CRUD Kelas | Kelola kelas per tahun ajaran & semester |
| Anggota Kelas | Assign siswa ke kelas |
| Import Anggota Kelas (Excel) | Import massal penempatan siswa |
| Kenaikan Kelas | Proses naik kelas massal |
| Proses Kelulusan | Proses kelulusan massal |
| Jadwal Kelas | Lihat jadwal pelajaran per kelas |

</details>

<details>
<summary><b>👨‍💼 Modul Kepegawaian</b> — 9 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| CRUD Data Pegawai | Tambah, ubah, hapus, lihat detail guru & staf |
| Import Pegawai (Excel) | Import massal dari Excel |
| Generate Akun Pegawai | Buat akun login individu / massal |
| Upload Dokumen Pegawai | Berkas kepegawaian (SK, sertifikat, dll) |
| Kenaikan Gaji Berkala | Riwayat KGB |
| Kenaikan Pangkat | Riwayat kenaikan pangkat/golongan |
| Mutasi Pegawai | Proses mutasi/perpindahan |
| Pensiun Pegawai | Proses pensiun |
| Rekap Absensi Pegawai | Auto-fill profil & kalkulasi otomatis total jam kerja harian |

</details>

<details>
<summary><b>📚 Modul Akademik</b> — 8 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Master Mata Pelajaran | Kelola daftar mata pelajaran |
| Kode Guru / Penugasan | Assign guru ke mata pelajaran |
| Konfigurasi Waktu KBM | Atur slot jam pelajaran |
| Jadwal Pelajaran (KBM & Non-KBM) | Penjadwalan cerdas untuk KBM reguler maupun kegiatan khusus (Istirahat, Upacara, G7, MBG) |
| Kalender Akademik | Kelola event/kegiatan akademik |
| Tahun Ajaran | Master tahun ajaran (aktif/nonaktif) |
| Semester | Master semester terhubung tahun ajaran |
| Jadwal Mengajar Guru | Lihat jadwal mengajar + download PDF |

</details>

<details>
<summary><b>📋 Modul Piket Harian</b> — 7 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Jadwal Petugas Piket | Atur jadwal piket mingguan guru |
| Jurnal Piket Harian | Dashboard operasional piket per hari |
| Catatan Harian | Catat kejadian/berita acara harian |
| Izin Keluar Siswa | Catat izin keluar + jam kembali |
| Izin Keluar Pegawai | Catat izin keluar + jam kembali |
| Absensi Siswa (Manual) | Catat ketidakhadiran (Sakit/Izin/Alpha) |
| Absensi Pegawai (Manual) | Catat ketidakhadiran + instruksi tindak lanjut kelas |

</details>

<details>
<summary><b>🏅 Modul Ekstrakurikuler</b> — 5 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| CRUD Ekstrakurikuler | Kelola data ekskul (nama, pembina, jadwal) |
| Anggota Ekskul | Kelola peserta ekskul |
| Absensi Ekskul | Rekap kehadiran peserta |
| Prestasi Ekskul | Catat prestasi/juara |
| Rekap Wali Kelas | Laporan keikutsertaan ekskul khusus untuk pantauan wali kelas |

</details>

<details>
<summary><b>🧑‍⚕️ Modul Bimbingan Konseling (BK)</b> — 5 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Jurnal Harian BK | Catatan harian kegiatan BK |
| Pelanggaran Siswa | Catat pelanggaran siswa |
| Siswa Terlambat | Catat keterlambatan |
| Pemanggilan Orang Tua | Catat panggilan orang tua |
| Alih Kasus (Referral) | Referral kasus ke pihak lain |

</details>

<details>
<summary><b>🏢 Modul Sarana Prasarana</b> — 4 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Master Gedung | Kelola data gedung sekolah |
| Master Ruangan | Kelola ruangan dalam gedung |
| Inventaris Barang | Kelola inventaris per ruangan |
| Peminjaman Sarpras | Peminjaman + tracking pengembalian |

</details>

<details>
<summary><b>📬 Modul Persuratan Digital & Disposisi</b> — 12 Fitur Unggulan</summary>

| Fitur | Deskripsi |
|-------|-----------|
| **Master Jenis Surat & Klasifikasi** | Kelola aturan penomoran otomatis, kode klasifikasi (e.g. `400.3.5.6`), dan template format nomor (+ Import & Export Excel) |
| **Arsip Surat Masuk** | Registrasi surat dinas luar lengkap dengan upload PDF, sifat surat, dan pencatatan penerima |
| **Lembar Disposisi Digital** | Alur instruksi pimpinan (Kepala Sekolah ke Guru/Staf) lengkap dengan sifat tugas, catatan instruksi, dan audit log status |
| **Upload Dokumen (.docx / .pdf)** | Staf TU bebas mengunggah langsung draf surat (.docx Word / .pdf / .xlsx Excel) maupun mengetik via Rich Text Editor Quill |
| **Engine Tag Auto-Replacement Word (.docx)** | Penggantian otomatis tag `[kode]`, `[nomor]`, `[bulan]`, `[tahun]`, `[tanggal_surat]`, dan `[penandatangan_id]` di dalam XML file Word |
| **Blok TTD Multi-Baris Kepsek** | Tag `[penandatangan_id]` otomatis mekar menjadi 3 baris resmi: Nama (Bold+Underline), Pangkat/Golongan (Plain), & NIP (Plain) |
| **Template SPPD 3 Halaman** | Paket Surat Perintah Perjalanan Dinas (SPPD) 3 halaman otomatis (Surat Tugas, Tabel 10 Poin, & Tabel Visum Berangkat/Tiba) |
| **Lampiran Tabel Excel Dinamis** | Membaca baris pertama Excel sebagai header tabel lampiran peserta/pegawai secara otomatis |
| **Auto-Fill Pegawai & Siswa** | Tombol cepat untuk memasukkan biodata lengkap pegawai/siswa ke dalam editor surat |
| **Alur Persetujuan (Approval)** | Alur pengajuan draf oleh Staf TU dan persetujuan resmi oleh Kepala Sekolah |
| **Edit Draf & Re-Upload Berkas** | Fitur ubah perihal, tujuan, tanggal, penandatangan, atau re-upload file berkas Word/PDF draf surat |
| **Hapus & Modal Popup Kustom** | Fitur hapus data surat, jenis surat, dan surat masuk dilengkapi modal konfirmasi popup kustom bergaya modern |

</details>

<details>
<summary><b>💬 Modul Chat Internal</b> — 4 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Chat Pribadi | Pesan 1-on-1 antar pengguna |
| Chat Grup | Buat & kelola grup obrolan |
| Multi-Media | Kirim teks, gambar, file, video, audio |
| Real-time Polling | Update pesan otomatis via Alpine.js |

</details>

<details>
<summary><b>🛠️ Utilitas & Pengaturan</b> — 9 fitur</summary>

| Fitur | Deskripsi |
|-------|-----------|
| Profil Pengguna | Edit profil, ubah password, hapus akun |
| Pengaturan Logo & Favicon | Upload logo header, sidebar, favicon |
| Tentang Sekolah | Kelola konten "Tentang" |
| Profil Identitas Sekolah | NPSN, alamat, akreditasi |
| Footer Links | Kelola tautan footer website |
| Menu Sidebar Dinamis | Kustomisasi urutan, ikon, permission |
| Log Aktivitas | Audit trail seluruh aksi CRUD |
| Backup & Restore | Backup database (Spatie Backup) |
| Pusat Download | Unduh PDF & Excel: Absensi Guru (16 Pertemuan), Absensi Wali Kelas (31 Hari Bulanan), jadwal, absensi ekskul, kartu siswa terpadu |

</details>

---

## 🏗️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | PHP 8.2+, Laravel 11 |
| **Frontend** | Blade, Tailwind CSS 3, Alpine.js 3 |
| **Reactive UI** | Livewire 4 |
| **Database** | PostgreSQL 18 |
| **Cache & Queue** | Redis |
| **Bundler** | Vite 6 |
| **Container** | Docker (Laravel Sail) |
| **PDF** | Barryvdh DomPDF |
| **Excel** | Maatwebsite Excel 3 |
| **Media** | Spatie Media Library 11 |
| **Backup** | Spatie Laravel Backup 9 |
| **Image Processing** | Intervention Image 4 |
| **Wilayah Indonesia** | Laravolt Indonesia |
| **Auth Scaffolding** | Laravel Breeze |
| **Email Testing** | Mailpit |

---

## 📋 Prasyarat

Pastikan PC Server (Windows 10/11) telah terpasang:

| Software | Versi Minimum | Keterangan |
|----------|---------------|------------|
| **WSL 2** | Windows 10 v2004+ | Windows Subsystem for Linux dengan distro **Ubuntu** |
| **Docker Desktop** | 4.x | Aktifkan opsi *WSL Integration* untuk Ubuntu |
| **Git** | 2.x | Untuk clone repositori |

> 💡 **Tip**: Pastikan virtualisasi (VT-x/AMD-V) sudah diaktifkan di BIOS PC Server.

---

## 🛠️ Instalasi & Deployment

### 1. Clone Repositori

Buka terminal **Ubuntu WSL** di PC Server:

```bash
git clone <URL_REPOSITORI_ANDA> ~/sias-akademik
cd ~/sias-akademik
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
nano .env
```

Sesuaikan nilai berikut:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://<IP_ADDRESS_PC_SERVER>

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=sistem_sekolah
DB_USERNAME=sail
DB_PASSWORD=password_yang_kuat

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=email-sekolah@gmail.com
MAIL_PASSWORD=app-password-google
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="sias@sekolah.sch.id"
```

### 3. Install Dependencies PHP

Jika folder `vendor/` belum tersedia:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs --no-scripts
```

### 4. Jalankan Docker Container

```bash
./vendor/bin/sail up -d
```

Pastikan semua 4 service berjalan:

| Service | Fungsi | Port |
|---------|--------|------|
| `laravel.test` | Aplikasi Web | 80 |
| `pgsql` | PostgreSQL 18 Database | 5432 |
| `redis` | Cache & Session | 6379 |
| `mailpit` | Email Testing Dashboard | 8025 |

### 5. Inisialisasi Aplikasi

```bash
# Generate application key
./vendor/bin/sail artisan key:generate

# Migrasi struktur tabel
./vendor/bin/sail artisan migrate

# Publikasi & migrasi tabel wilayah Indonesia
./vendor/bin/sail artisan indonesia:publish
./vendor/bin/sail artisan migrate

# Seed data wilayah Indonesia (80.000+ data, memakan waktu beberapa menit)
./vendor/bin/sail artisan indonesia:seed

# Seed role, permission, menu, & akun admin default
./vendor/bin/sail artisan db:seed

# Buat symbolic link untuk file upload
./vendor/bin/sail artisan storage:link
```

### 6. Build Aset Frontend

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 7. Optimasi Production

```bash
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

### 8. Buka Akses Jaringan Lokal

Agar perangkat lain di jaringan Wi-Fi/LAN bisa mengakses sistem, buka **PowerShell (Run as Administrator)** di Windows:

```powershell
New-NetFirewallRule -DisplayName "SIAS AKADEMIK Port 80" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow
```

Cek IP lokal server:

```powershell
ipconfig
```

---

## 🔑 Akun Default

Setelah seeding selesai, gunakan akun berikut untuk login pertama kali:

| Field | Nilai |
|-------|-------|
| **Email** | `admin@sias.com` |
| **Password** | `password123` |
| **Role** | Super Administrator |

> ⚠️ **PENTING**: Segera ganti password ini setelah login pertama!

---

## 🧑‍🤝‍🧑 Sistem Role & Permission

### Role Default (dari Seeder)

| Role | Display Name | Akses |
|------|-------------|-------|
| `admin` | Super Administrator | Seluruh 112 permission (akses penuh) |
| `guru` | Guru Akademik | Lihat data akademik & blog |
| `siswa` | Siswa Aktif | Lihat profil sekolah |

### Menambah Role Baru

Login sebagai Admin → **Master Data → Master Role** untuk menambahkan role sesuai kebutuhan, misalnya:

- Kepala Sekolah
- Wakil Kepala Sekolah
- Tata Usaha
- Guru BK
- Wali Murid

Setiap role dapat diatur permission-nya secara granular melalui panel **Master Data → Hak Akses**.

### Distribusi Permission per Modul

| Modul | Jumlah Permission |
|-------|-------------------|
| Sistem Pengguna | 17 |
| Akademik & Penjadwalan | 17 |
| Sarana Prasarana | 18 |
| Kesiswaan | 8 |
| Kepegawaian | 4 |
| Ekstrakurikuler | 9 |
| Piket Harian | 12 |
| Portal Berita | 11 |
| Pengaturan Website | 20 |
| Bimbingan Konseling | 15 |
| Persuratan | 16 |
| **Total** | **112** |

---

## 📁 Struktur Proyek

```
sias-akademik/
├── app/
│   ├── Exports/              # 6 export Excel/PDF template
│   ├── Http/
│   │   ├── Controllers/      # 56 controller di 11 subdirektori
│   │   │   ├── Akademik/           # Mata pelajaran, kode guru, waktu KBM, jadwal
│   │   │   ├── BK/                 # Jurnal BK, kedisiplinan, penanganan kasus
│   │   │   ├── Ekskul/             # Manajemen ekstrakurikuler
│   │   │   ├── Kepegawaian/        # Pegawai, dokumen, KGB, pangkat
│   │   │   ├── Kesiswaan/          # Siswa, kelas, dokumen siswa
│   │   │   ├── Master/             # 18 controller pengaturan sistem
│   │   │   ├── Piket/              # Petugas & jurnal piket harian
│   │   │   ├── Publik/             # Blog, halaman, kontak publik
│   │   │   ├── Sarpras/            # Gedung, ruangan, peminjaman
│   │   │   └── Surat/              # Jenis surat, masuk, keluar
│   │   ├── Middleware/       # CheckPermission, CheckApproval
│   │   └── Requests/         # Form request validation
│   ├── Imports/              # 4 import handler Excel
│   ├── Models/               # 61 model Eloquent
│   └── Traits/               # Loggable, ImageCompressor
├── database/
│   ├── migrations/           # 71 file migrasi
│   └── seeders/              # Role, Permission, Menu seeder
├── resources/
│   └── views/                # 100+ Blade template
│       ├── akademik/         # View akademik & jadwal
│       ├── auth/             # Login, register, dll
│       ├── bk/               # View bimbingan konseling
│       ├── chat/             # Chat interface
│       ├── dashboard/        # 5 dashboard (admin, guru, staf, siswa, default)
│       ├── ekskul/           # View ekstrakurikuler
│       ├── kepegawaian/      # View kepegawaian
│       ├── kesiswaan/        # View kesiswaan & kelas
│       ├── master/           # 18 subdirektori pengaturan
│       ├── piket/            # View piket harian
│       ├── publik/           # Blog & halaman publik
│       ├── pusat_download/   # Download center
│       ├── sarpras/          # View sarana prasarana
│       └── surat/            # View persuratan
├── routes/
│   ├── web.php               # ~150 rute web
│   └── auth.php              # Rute autentikasi
├── docker/                   # Dockerfile & konfigurasi container
├── compose.yaml              # Docker Compose (4 service)
├── tailwind.config.js        # Konfigurasi Tailwind CSS
├── vite.config.js            # Konfigurasi Vite bundler
└── ...
```

---

## 📊 Statistik Proyek

| Metrik | Jumlah |
|--------|--------|
| Total Fitur | **88** |
| Modul Utama | **13** |
| Permission | **112** |
| Model Database | **61** |
| Migrasi | **71** |
| Controller | **56** |
| View Blade | **100+** |
| Export Template | **6** |
| Import Handler | **4** |
| Menu Sidebar | **35 item** |
| Data Wilayah | **80.000+** |
| Docker Services | **4** |

---

## 🔗 Integrasi Ekosistem (SSO & E-Antrian)

SIAS dirancang bukan hanya sebagai aplikasi mandiri, tetapi juga sebagai **Pusat Identitas (SSO Provider)** dan backend untuk aplikasi ekosistem sekolah lainnya, seperti **Aplikasi E-Antrian** dan **Aplikasi Mobile Android Absensi (AbsensiSiswaFourbit)**.

### 1. Aplikasi Mobile Android (AbsensiSiswaFourbit):
- **Hybrid Native Scanner:** Menggunakan WebView + ZXing Native Scanner untuk membypass kendala HTTP/non-SSL.
- **Kamera Speed & Focus:** Kecepatan dan fokus pemindaian tinggi langsung dari kamera HP.
- **Pembersihan Cache Otomatis:** WebView dipaksa mengambil tampilan web paling segar dari server secara *real-time*.

### 2. Aplikasi E-Antrian:
1. **Single Sign-On (SSO):** Petugas atau Admin e-Antrian dapat login menggunakan kredensial SIAS.
2. **Sinkronisasi Sesi Chat:** Fitur *Ruang Obrolan* e-Antrian terhubung langsung dengan modul Chat SIAS.
3. **Pusat Data Pegawai:** Semua manajemen data petugas terpusat di database SIAS.

### Konfigurasi Penting (Bagi Pengembang):
Untuk memastikan aplikasi eksternal (seperti e-Antrian) dapat terhubung dengan SIAS, pastikan SIAS dapat diakses melalui IP Publik atau Domain yang sama. Pada file `.env` aplikasi eksternal, biasanya diperlukan URL Publik SIAS, contoh:
```env
SIAS_PUBLIC_URL=http://<IP_ADDRESS_SIAS>:8080
SIAS_API_URL=http://<IP_INTERNAL_SIAS_ATAU_DOCKER_HOST>:8080
```

---

## 🔧 Perintah Berguna

### Development

```bash
# Jalankan server development
./vendor/bin/sail up -d

# Watch perubahan frontend (hot reload)
./vendor/bin/sail npm run dev

# Jalankan semua service sekaligus (server + queue + logs + vite)
./vendor/bin/sail composer dev
```

### Database

```bash
# Migrasi ulang (⚠️ HAPUS SEMUA DATA)
./vendor/bin/sail artisan migrate:fresh --seed

# Jalankan seeder saja
./vendor/bin/sail artisan db:seed

# Seed data wilayah Indonesia
./vendor/bin/sail artisan indonesia:seed
```

### Maintenance

```bash
# Bersihkan semua cache
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear

# Rebuild cache untuk production
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache

# Rebuild aset frontend
./vendor/bin/sail npm run build
```

### Backup Database

```bash
# Via artisan command
./vendor/bin/sail artisan backup:run --only-db
```

> 💡 Backup juga bisa dilakukan melalui panel Admin → **Backup & Restore Sistem**.

---

## 🌐 Cara Mengakses

Setelah deployment berhasil, buka browser di perangkat yang terhubung ke jaringan Wi-Fi/LAN yang sama:

```
http://<IP_ADDRESS_PC_SERVER>
```

**Contoh:**

```
http://192.168.1.50
```

| Halaman | URL |
|---------|-----|
| Landing Page | `http://<IP>/` |
| Blog Sekolah | `http://<IP>/blog` |
| Kontak | `http://<IP>/contact` |
| Login | `http://<IP>/login` |
| Register | `http://<IP>/register` |
| Dashboard | `http://<IP>/dashboard` |

---

## ✅ Checklist Pasca-Deployment

Setelah sistem berhasil berjalan, lakukan langkah berikut:

- [ ] Login dengan akun admin default (`admin@sias.com` / `password123`)
- [ ] **Ganti password admin** ke password yang kuat
- [ ] Buat role tambahan sesuai kebutuhan (Kepala Sekolah, TU, Guru BK, dll)
- [ ] Assign permission ke setiap role baru
- [ ] Isi **Profil Sekolah** (NPSN, alamat, akreditasi)
- [ ] Upload **Logo Sekolah** (header, sidebar, favicon)
- [ ] Buat **Tahun Ajaran** dan **Semester** aktif
- [ ] Isi **Mata Pelajaran** dan konfigurasi **Waktu KBM**
- [ ] Import data **Pegawai / Guru** dari Excel
- [ ] Import data **Siswa + Wali Murid** dari Excel
- [ ] Buat **Kelas** dan assign anggota kelas
- [ ] Atur **Jadwal Pelajaran** per kelas
- [ ] Konfigurasi **Email SMTP** untuk fitur reset password
- [ ] Isi konten **Tentang Sekolah** dan **Halaman Dinamis**
- [ ] Sosialisasi sistem ke seluruh guru dan staf

---

## 🔄 Pembaruan Kode

Jika ada pembaruan kode di kemudian hari, jalankan langkah berikut di terminal WSL:

```bash
cd ~/sias-akademik

# Tarik perubahan terbaru
git pull origin main

# Update dependencies
./vendor/bin/sail composer install --no-dev --optimize-autoloader
./vendor/bin/sail npm install

# Jalankan migrasi baru (jika ada)
./vendor/bin/sail artisan migrate

# Rebuild aset & cache
./vendor/bin/sail artisan view:clear
./vendor/bin/sail npm run build
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat *pull request* atau buka *issue* untuk saran dan perbaikan.

1. Fork repositori ini
2. Buat branch fitur (`git checkout -b fitur/FiturBaru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur/FiturBaru`)
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<div align="center">

**Created by Samuel Meyer Sanger, MTCRE**

</div>