<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner Absensi - SIAS</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-slate-50 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    
    {{-- Elemen Dekoratif Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-indigo-500/20 blur-[120px] mix-blend-multiply animate-pulse"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-cyan-400/20 blur-[120px] mix-blend-multiply animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-[30%] left-[40%] w-[30vw] h-[30vw] rounded-full bg-emerald-400/10 blur-[100px] mix-blend-multiply animate-bounce" style="animation-duration: 8s;"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDAuNWg0ME0wIDM5LjVoNDBNMC41IDB2NDBNMzkuNSAwdi00MCIgc3Ryb2tlPSJyZ2JhKDE1LCAyMywgNDIsIDAuMDMpIiBzdHJva2Utd2lkdGg9IjEiLz48L3N2Zz4=')] opacity-50"></div>
    </div>

    {{-- Kontainer Utama --}}
    <div class="relative z-10 min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-3xl mx-auto">
            
            <div class="text-center mb-8 relative">
                @php
                    $logoSetting = \DB::table('pengaturan_logo')->first();
                    $logoSekolah = $logoSetting && $logoSetting->logo_sekolah 
                        ? asset('storage/' . $logoSetting->logo_sekolah) 
                        : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
                @endphp
                <a href="/" class="inline-flex justify-center items-center mb-6 w-20 h-20 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-3 transform transition-transform hover:scale-105">
                    <img src="{{ $logoSekolah }}" class="w-full h-full object-contain" alt="Logo Sekolah" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg'">
                </a>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight text-slate-900 mb-3">Portal Presensi QR</h1>
                <p class="text-slate-500 font-medium text-sm md:text-base">Arahkan ID Card atau QR Code Siswa ke arah lensa pemindai</p>
            </div>

            <style>
                /* Override styling bawaan html5-qrcode yang bentrok dengan Tailwind */
                #reader button {
                    background-color: #4f46e5 !important;
                    color: white !important;
                    padding: 10px 20px !important;
                    border-radius: 12px !important;
                    font-weight: 900 !important;
                    font-size: 12px !important;
                    text-transform: uppercase !important;
                    letter-spacing: 1px !important;
                    cursor: pointer !important;
                    transition: all 0.3s !important;
                    border: none !important;
                    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3) !important;
                }
                #reader button:hover {
                    background-color: #4338ca !important;
                }
                #reader select {
                    padding: 10px !important;
                    border-radius: 12px !important;
                    border: 1px solid #cbd5e1 !important;
                    color: #0f172a !important;
                    font-weight: 600 !important;
                    margin: 10px 0 !important;
                    width: 100% !important;
                    max-width: 300px !important;
                }
                #reader a {
                    color: #4f46e5 !important;
                    text-decoration: none !important;
                    font-weight: 600 !important;
                }
                #reader__dashboard_section_csr span {
                    color: #64748b !important; /* text-slate-500 */
                    font-weight: 600 !important;
                }
                #reader__scan_region {
                    background-color: #f8fafc !important; /* bg-slate-50 */
                }
            </style>

            <div class="bg-white/80 backdrop-blur-2xl rounded-[3rem] p-6 md:p-10 shadow-2xl shadow-slate-200/50 border border-white/60 relative overflow-hidden group">
                
                <!-- Tempat Scanner Kamera -->
                <div id="reader" class="w-full rounded-3xl overflow-hidden shadow-inner bg-white border-2 border-slate-100 relative z-10 p-2"></div>
                
                <div class="mt-8 text-center relative z-10">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center justify-center gap-3">
                        <span class="w-8 h-[1px] bg-slate-200"></span> ATAU <span class="w-8 h-[1px] bg-slate-200"></span>
                    </p>
                    <!-- Input ini otomatis difokuskan agar bisa menerima input dari scanner tembak -->
                    <div class="relative max-w-sm mx-auto">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-barcode text-slate-400"></i>
                        </div>
                        <input type="text" id="manual_nisn" class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl font-black tracking-[0.2em] focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none shadow-sm transition-all" placeholder="NISN MANUAL" autofocus>
                    </div>
                </div>

                <!-- Area Notifikasi Berhasil/Gagal -->
                <div id="result-alert" class="hidden mt-8 p-6 rounded-2xl border flex items-center gap-5 transition-all transform duration-300 shadow-lg">
                    <div id="alert-icon" class="text-4xl drop-shadow-md"></div>
                    <div class="flex-1">
                        <h3 id="alert-title" class="font-black text-lg tracking-tight mb-1"></h3>
                        <p id="alert-message" class="text-sm font-bold opacity-90 leading-snug"></p>
                    </div>
                </div>
                
                <!-- Efek Glow Belakang -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-indigo-500/20 transition-colors duration-700"></div>
            </div>
            
            <div class="mt-10 text-center">
                <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-sm font-black uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let isProcessing = false;

        function processScan(nisn) {
            if (isProcessing) return;
            isProcessing = true;

            // Visual feedback
            document.getElementById('manual_nisn').value = '';
            const alertBox = document.getElementById('result-alert');
            alertBox.className = 'mt-8 p-6 rounded-2xl border flex items-center gap-5 bg-indigo-50 border-indigo-200 text-indigo-800 shadow-lg shadow-indigo-500/20 block transform scale-95 opacity-0';
            
            // Animasi pop in
            setTimeout(() => {
                alertBox.classList.remove('scale-95', 'opacity-0');
                alertBox.classList.add('scale-100', 'opacity-100');
            }, 50);

            document.getElementById('alert-icon').innerHTML = '⏳';
            document.getElementById('alert-title').innerText = 'Memverifikasi Data...';
            document.getElementById('alert-message').innerText = 'Mohon tunggu sebentar.';

            fetch('{{ route("publik.absensi-qr.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nisn: nisn })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alertBox.className = 'mt-8 p-6 rounded-2xl border flex items-center gap-5 bg-emerald-50 border-emerald-200 text-emerald-800 shadow-lg shadow-emerald-500/20 block transform scale-100 opacity-100';
                    document.getElementById('alert-icon').innerHTML = '✅';
                    document.getElementById('alert-title').innerText = data.siswa.nama + ' (' + data.siswa.kelas + ')';
                    document.getElementById('alert-message').innerText = data.message + ' jam ' + data.siswa.waktu;
                    
                    // Play success sound
                    playBeep(800, 150);
                } else if (data.status === 'warning') {
                    alertBox.className = 'mt-8 p-6 rounded-2xl border flex items-center gap-5 bg-amber-50 border-amber-200 text-amber-800 shadow-lg shadow-amber-500/20 block transform scale-100 opacity-100';
                    document.getElementById('alert-icon').innerHTML = '⚠️';
                    document.getElementById('alert-title').innerText = 'Perhatian';
                    document.getElementById('alert-message').innerText = data.message;
                    
                    playBeep(400, 300);
                } else {
                    alertBox.className = 'mt-8 p-6 rounded-2xl border flex items-center gap-5 bg-rose-50 border-rose-200 text-rose-800 shadow-lg shadow-rose-500/20 block transform scale-100 opacity-100';
                    document.getElementById('alert-icon').innerHTML = '❌';
                    document.getElementById('alert-title').innerText = 'Gagal';
                    document.getElementById('alert-message').innerText = data.message;
                    
                    playBeep(300, 400);
                }

                // Reset state
                setTimeout(() => {
                    alertBox.classList.remove('scale-100', 'opacity-100');
                    alertBox.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        alertBox.classList.add('hidden');
                        isProcessing = false;
                        document.getElementById('manual_nisn').focus();
                    }, 300);
                }, 3000);
            })
            .catch(error => {
                alertBox.className = 'mt-8 p-6 rounded-2xl border flex items-center gap-5 bg-rose-50 border-rose-200 text-rose-800 shadow-lg shadow-rose-500/20 block transform scale-100 opacity-100';
                document.getElementById('alert-icon').innerHTML = '❌';
                document.getElementById('alert-title').innerText = 'Error Jaringan';
                document.getElementById('alert-message').innerText = 'Terjadi kesalahan saat memproses data.';
                
                setTimeout(() => {
                    alertBox.classList.remove('scale-100', 'opacity-100');
                    alertBox.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        alertBox.classList.add('hidden');
                        isProcessing = false;
                        document.getElementById('manual_nisn').focus();
                    }, 300);
                }, 3000);
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            processScan(decodedText);
        }

        function onScanFailure(error) {
            // Abaikan error saat tidak ada QR yang terdeteksi
        }

        // Inisialisasi Kamera HTML5 QR Code
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        // Tangkap input dari Scanner USB
        document.getElementById('manual_nisn').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const nisn = this.value.trim();
                if (nisn !== '') {
                    processScan(nisn);
                }
            }
        });

        // Sound effect generator sederhana
        function playBeep(freq, dur) {
            try {
                const context = new (window.AudioContext || window.webkitAudioContext)();
                const osc = context.createOscillator();
                const gain = context.createGain();
                osc.connect(gain);
                gain.connect(context.destination);
                osc.frequency.value = freq;
                osc.type = "sine";
                osc.start();
                setTimeout(function() {
                    osc.stop();
                }, dur);
            } catch (e) {
                console.log("Audio not supported");
            }
        }
    </script>
</body>
</html>
