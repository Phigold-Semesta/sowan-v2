@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 transition-colors duration-500 bg-slate-50 dark:bg-slate-950 min-h-screen">
    {{-- 1. Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-sm">
                Dashboard Pimpinan
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 tracking-wide">
                Monitoring Strategis LPSE Karawang | 
                <span class="ml-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] rounded-full italic border border-emerald-200 dark:border-emerald-800">
                    SOWAN V2 - Akses Eksekutif
                </span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Waktu Server</p>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ now()->format('d M Y | H:i') }} WIB</p>
        </div>
    </div>

    {{-- 2. Baris Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Total Kunjungan --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Total Kunjungan</p>
                    <h3 class="text-4xl font-black text-emerald-900 dark:text-emerald-100">{{ $stats['total_kunjungan'] }}</h3>
                </div>
                <div class="bg-emerald-50 p-3 rounded-2xl text-xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">📊</div>
            </div>
        </div>

        {{-- Kunjungan Hari Ini --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Hadir Hari Ini</p>
                    <h3 class="text-4xl font-black text-slate-800 dark:text-slate-200">{{ $stats['kunjungan_hari_ini'] }}</h3>
                </div>
                <div class="bg-slate-100 p-3 rounded-2xl text-xl group-hover:bg-emerald-900 group-hover:text-white transition-all duration-500">🕒</div>
            </div>
        </div>

        {{-- Rating Rata-rata --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Rating Rata-rata</p>
                    <h3 class="text-4xl font-black text-emerald-600 tracking-tighter">{{ number_format($stats['rata_rata_rating'], 1) }}</h3>
                </div>
                <div class="bg-emerald-50 p-3 rounded-2xl text-xl group-hover:rotate-12 transition-all duration-500">⭐</div>
            </div>
        </div>

        {{-- Layanan Terpopuler --}}
        <div class="group bg-emerald-900 p-6 rounded-[2.5rem] shadow-2xl transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-400 flex flex-col justify-between">
            <div>
                <p class="text-emerald-300 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Layanan Terpopuler</p>
                <h3 class="text-lg font-black text-white leading-tight mt-2 line-clamp-2" title="{{ $stats['layanan_terpopuler'] ? $stats['layanan_terpopuler']->nama_layanan : '-' }}">
                    {{ $stats['layanan_terpopuler'] ? $stats['layanan_terpopuler']->nama_layanan : '-' }}
                </h3>
            </div>
            <div class="text-xl mt-4">🏆</div>
        </div>
    </div>

    {{-- 3. Grafik Tren Kunjungan --}}
    <div class="mb-10 bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl border border-slate-50 dark:border-slate-800">
        <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest mb-8 flex items-center gap-2">
            <span class="w-8 h-8 bg-emerald-900 text-white rounded-lg flex items-center justify-center text-xs">📈</span> Analisis Tren Kunjungan Mingguan
        </h3>
        <div class="h-[300px]">
            <canvas id="guestChart"></canvas>
        </div>
    </div>

    {{-- 4. Footer Banner --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 rounded-[2.5rem] p-8 text-white shadow-2xl border border-emerald-500/30">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">🎯</div>
                <div>
                    <h4 class="text-xl font-black uppercase italic text-white">Evaluasi Kinerja</h4>
                    <p class="text-emerald-100 text-sm">Gunakan data ini untuk pengambilan kebijakan strategis LPSE Karawang.</p>
                </div>
            </div>
            <a href="{{ route('pimpinan.laporan.index') }}" class="px-8 py-4 bg-white text-emerald-900 font-black rounded-2xl hover:scale-105 transition-all shadow-xl text-sm uppercase tracking-wider">
                Laporan Lengkap →
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('guestChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: [12, 19, 15, 25, 22, 10, 5],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } } }
        }
    });
</script>
@endsection