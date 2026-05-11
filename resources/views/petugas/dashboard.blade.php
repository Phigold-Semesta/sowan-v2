@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 transition-colors duration-500 bg-slate-50 dark:bg-slate-950 min-h-screen">
    {{-- 1. Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-sm">
                Dashboard <span class="text-emerald-700 dark:text-white">Petugas</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 tracking-wide">
                SOWAN V2 • Monitoring Kehadiran Tamu LPSE Karawang
                <span class="ml-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] rounded-full italic animate-pulse border border-emerald-200 dark:border-emerald-800">
                    ⚡ Helpdesk Aktif
                </span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Waktu Lokal</p>
            <p id="realtime-clock" class="text-sm font-bold text-emerald-900 dark:text-emerald-300 tracking-tight">{{ now()->format('d M Y | H:i:s') }} WIB</p>
        </div>
    </div>

    {{-- 2. Baris Statistik Utama (5 Card Eksklusif) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
        
        {{-- Card 1: Total Kunjungan Hari Ini --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100 dark:border-slate-800 transition-all duration-500 hover:-translate-y-2">
            <p class="text-slate-400 dark:text-slate-500 text-[9px] uppercase font-black tracking-[0.2em] mb-1 text-center">Total Kunjungan</p>
            <div class="flex flex-col items-center">
                <h3 class="text-4xl font-black text-emerald-900 dark:text-emerald-100 tracking-tighter">{{ number_format($stats['total_hari_ini'] ?? 0) }}</h3>
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase italic mt-1">Hari Ini</span>
            </div>
            <div class="mt-4 w-full h-1 bg-emerald-100 dark:bg-emerald-900/30 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 w-full animate-pulse"></div>
            </div>
        </div>

        {{-- Card 2: Belum Dilayani --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-xl shadow-red-900/5 border-b-4 border-red-500 transition-all duration-500 hover:-translate-y-2 text-center">
            <p class="text-red-500/60 dark:text-red-400 text-[9px] uppercase font-black tracking-[0.2em] mb-1 font-bold">Belum Dilayani</p>
            <div class="flex flex-col items-center">
                <h3 class="text-4xl font-black text-red-600 dark:text-red-400 tracking-tighter">{{ $stats['belum'] ?? 0 }}</h3>
                <div class="flex items-center gap-1 mt-1">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Antrean</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Sedang Dilayani --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border-b-4 border-blue-500 transition-all duration-500 hover:-translate-y-2 text-center">
            <p class="text-blue-500/60 dark:text-blue-400 text-[9px] uppercase font-black tracking-[0.2em] mb-1 font-bold">Sedang Proses</p>
            <div class="flex flex-col items-center">
                <h3 class="text-4xl font-black text-blue-600 dark:text-blue-400 tracking-tighter">{{ $stats['sedang'] ?? 0 }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase mt-1">Melayani</span>
            </div>
        </div>

        {{-- Card 4: Sudah Dilayani --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border-b-4 border-emerald-500 transition-all duration-500 hover:-translate-y-2 text-center">
            <p class="text-emerald-500/60 dark:text-emerald-400 text-[9px] uppercase font-black tracking-[0.2em] mb-1 font-bold">Selesai</p>
            <div class="flex flex-col items-center">
                <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">{{ $stats['sudah'] ?? 0 }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic">Real-time</span>
            </div>
        </div>

        {{-- Card 5: RATING LAYANAN (Sesuai Gambar Admin - Mewah & Ikonik) --}}
        @php
            $avgRating = $stats['avg_rating'] ?? 0;
            $ratingPercentage = ($avgRating / 5) * 100;
        @endphp
        <div class="group bg-[#064e3b] dark:bg-emerald-950 p-6 rounded-[2.5rem] shadow-xl shadow-emerald-900/20 border border-emerald-500/20 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <p class="text-emerald-400 text-[9px] uppercase font-black tracking-[0.2em]">Rating Layanan</p>
                <span class="text-2xl animate-bounce">🤩</span>
            </div>
            <div class="flex flex-col">
                <h3 class="text-3xl font-black text-white tracking-tighter">
                    {{ number_format($avgRating, 1) }}<span class="text-xs opacity-50 ml-1">/ 5.0</span>
                </h3>
                <div class="w-full bg-emerald-900/50 dark:bg-slate-800 h-2 rounded-full mt-4 overflow-hidden border border-emerald-700/30">
                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-300 h-full transition-all duration-1000 shadow-[0_0_15px_rgba(52,211,153,0.6)]" style="width: {{ $ratingPercentage }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Baris Visualisasi (Grafik & Tujuan Utama) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 items-stretch">
        {{-- Grafik Analisis Mingguan (Gaya Admin) --}}
        <div class="lg:col-span-2 flex">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3.5rem] shadow-2xl shadow-emerald-900/5 border border-slate-50 dark:border-slate-800 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest flex items-center gap-3">
                        <span class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">📈</span>
                        Analisis Mingguan
                    </h3>
                </div>
                <div class="flex-grow min-h-[350px]">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Panel Tujuan Utama (Gaya Admin - Donut Chart) --}}
        <div class="flex flex-col gap-6">
            {{-- Registrasi Tamu Manual Button --}}
            <a href="{{ route('petugas.manajemen_tamu.create') }}" class="group block w-full bg-emerald-600 dark:bg-emerald-500 p-8 rounded-[3rem] shadow-xl text-white relative overflow-hidden transition-all hover:scale-[1.02] active:scale-95">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black tracking-tighter uppercase italic mb-1">Registrasi Tamu Manual</h3>
                    <p class="text-emerald-100/70 text-[10px] font-bold uppercase tracking-widest">Input Data Tamu Baru →</p>
                </div>
            </a>

            {{-- Card Tujuan Utama (Menggantikan Log Aktivitas seperti di Admin) --}}
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3.5rem] shadow-2xl border border-slate-50 dark:border-slate-800 flex-grow">
                <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">🎯</span>
                    Tujuan Utama
                </h3>
                
                <div class="relative flex justify-center items-center mb-6">
                    <canvas id="tujuanChart" width="200" height="200"></canvas>
                </div>

                <div class="space-y-4">
                    {{-- Progres Bar Statis (Bisa dikoneksikan ke data dinamis nantinya) --}}
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase text-slate-500 mb-1">
                            <span>Konsultasi LPSE</span>
                            <span class="text-emerald-600">65%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-black uppercase text-slate-500 mb-1">
                            <span>Verifikasi Berkas</span>
                            <span class="text-emerald-600">20%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Footer Fast Access --}}
    <div class="bg-gradient-to-r from-emerald-950 to-emerald-800 rounded-[3rem] p-8 text-white shadow-2xl shadow-emerald-900/30 border border-emerald-500/20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 px-4">
            <div class="text-center md:text-left">
                <h4 class="text-2xl font-black tracking-tight uppercase italic text-emerald-50">Navigasi Operasional</h4>
                <p class="text-emerald-300/70 text-xs font-medium italic">Manajemen antrean tamu LPSE Karawang SOWAN V2.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('petugas.manajemen_tamu.index') }}" class="px-8 py-4 bg-white text-emerald-900 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all text-xs uppercase tracking-widest shadow-lg">
                    Manajemen Tamu 👥
                </a>
                <a href="{{ route('petugas.laporan.index') }}" class="px-8 py-4 bg-emerald-400 text-emerald-950 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all text-xs uppercase tracking-widest shadow-lg">
                    Cetak Laporan 📂
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Realtime Clock
    setInterval(() => {
        const clockElement = document.getElementById('realtime-clock');
        if(clockElement) {
            clockElement.textContent = new Date().toLocaleDateString('id-ID', { 
                day: '2-digit', month: 'short', year: 'numeric', 
                hour: '2-digit', minute: '2-digit', second: '2-digit' 
            }) + ' WIB';
        }
    }, 1000);

    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        
        // --- 1. GRAFIK ANALISIS MINGGUAN (Line Chart) ---
        const gCtx = document.getElementById('visitorChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const dataSet = {!! json_encode($chartData) !!};

        const gGrad = gCtx.createLinearGradient(0, 0, 0, 400);
        gGrad.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(gCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Tamu',
                    data: dataSet,
                    borderColor: '#10b981',
                    backgroundColor: gGrad,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 4,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointHoverBorderWidth: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: 'bold' } }
                    }
                }
            }
        });

        // --- 2. GRAFIK TUJUAN UTAMA (Donut Chart - Persis Admin) ---
        const tCtx = document.getElementById('tujuanChart').getContext('2d');
        new Chart(tCtx, {
            type: 'doughnut',
            data: {
                labels: ['Konsultasi', 'Verifikasi', 'Lainnya'],
                datasets: [{
                    data: [65, 20, 15],
                    backgroundColor: ['#059669', '#34d399', '#ecfdf5'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '80%',
                responsive: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>

<style>
    canvas { filter: drop-shadow(0 15px 25px rgba(16, 185, 129, 0.15)); }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
</style>
@endsection