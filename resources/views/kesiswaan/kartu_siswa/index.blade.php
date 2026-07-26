@extends('layouts.app')

@section('content')
<div class="bg-indigo-600 rounded-b-3xl pb-8 pt-8 -mt-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-indigo-700/50 mix-blend-multiply"></div>
    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500 rounded-full blur-3xl opacity-50 translate-x-1/2 -translate-y-1/2"></div>
    
    <div class="relative z-10 px-8">
        <div class="flex items-center gap-4 text-white/80 mb-4">
            <span class="p-2 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-inner">
                <svg class="w-5 h-5 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                </svg>
            </span>
            <div class="flex items-center gap-2 text-sm font-medium tracking-wide">
                <span>Kesiswaan</span>
                <span class="text-indigo-300">/</span>
                <span class="text-white">Cetak Kartu Siswa</span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-black text-white tracking-tight mb-3">Cetak Kartu Pelajar & QR Code</h1>
                <p class="text-indigo-100/90 text-lg font-medium leading-relaxed">
                    Generate dan cetak kartu siswa (format Kemendikdasmen) terintegrasi dengan kode QR untuk absensi elektronik.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="px-8 -mt-6 relative z-20">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
        <form action="{{ route('kesiswaan.kartu-siswa.cetak') }}" method="GET" target="_blank" class="max-w-xl mx-auto space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Kelas</label>
                <div class="relative">
                    <select name="kelas_id" required class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-medium appearance-none focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all hover:bg-white cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Kartu Kelas Ini
            </button>
            <p class="text-center text-sm text-slate-500 font-medium">Format cetak dioptimalkan untuk ukuran kertas A4 (Bisa memuat sekitar 5-6 kartu per halaman).</p>
        </form>
    </div>
</div>
@endsection
