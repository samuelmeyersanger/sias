<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ditutup - SIAS</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-slate-50 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white flex flex-col min-h-screen">
    
    {{-- Elemen Dekoratif Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-rose-500/10 blur-[120px] mix-blend-multiply animate-pulse"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-orange-400/10 blur-[120px] mix-blend-multiply animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-[30%] left-[40%] w-[30vw] h-[30vw] rounded-full bg-red-400/5 blur-[100px] mix-blend-multiply animate-bounce" style="animation-duration: 8s;"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDAuNWg0ME0wIDM5LjVoNDBNMC41IDB2NDBNMzkuNSAwdi00MCIgc3Ryb2tlPSJyZ2JhKDE1LCAyMywgNDIsIDAuMDMpIiBzdHJva2Utd2lkdGg9IjEiLz48L3N2Zz4=')] opacity-50"></div>
    </div>

    {{-- Kontainer Utama --}}
    <div class="relative z-10 flex-grow flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-xl mx-auto">
            
            <div class="bg-white/80 backdrop-blur-2xl rounded-[3rem] p-8 md:p-14 shadow-2xl shadow-rose-200/50 border border-white/60 relative overflow-hidden group text-center flex flex-col items-center">
                
                {{-- Aksen Sudut --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-rose-100 to-transparent opacity-50 rounded-bl-full pointer-events-none"></div>

                {{-- Ikon Animasi --}}
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-rose-200 rounded-full blur-xl animate-pulse opacity-50"></div>
                    <div class="relative p-6 rounded-[2rem] bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-xl shadow-rose-500/30 transform transition-transform duration-500 group-hover:scale-105 border border-rose-400">
                        <i class="fa-solid fa-lock text-4xl"></i>
                    </div>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 mb-3">Telah Ditutup</h1>
                
                <div class="h-1 w-16 bg-gradient-to-r from-rose-500 to-orange-500 rounded-full mb-6"></div>

                <p class="text-slate-500 font-medium text-sm md:text-base leading-relaxed mb-10">
                    Mohon maaf, halaman <strong>{{ $title ?? 'Pendaftaran' }}</strong> saat ini sudah ditutup dan tidak menerima tanggapan lagi. Silakan hubungi pihak tata usaha sekolah jika Anda memiliki pertanyaan.
                </p>

                <a href="/" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20 hover:-translate-y-1 w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-house"></i> Kembali ke Beranda
                </a>
                
            </div>
            
        </div>

    </div>

</body>
</html>
