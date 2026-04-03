@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 transition-colors duration-500 bg-slate-50 dark:bg-slate-950 min-h-screen">
    {{-- 1. Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-sm">
                Dashboard Admin
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 tracking-wide">
                Selamat datang kembali, <span class="text-emerald-600 dark:text-emerald-300">{{ Auth::user()->nama }}</span>! 
                <span class="ml-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] rounded-full italic animate-pulse border border-emerald-200 dark:border-emerald-800">
                    ⚡ Status Sistem: Optimal
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
        
        {{-- Total Petugas --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Total Petugas</p>
                    <h3 class="text-4xl font-black text-emerald-900 dark:text-emerald-100 tracking-tighter">{{ $totalUsers }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-lg shadow-emerald-100 dark:shadow-none">👥</div>
            </div>
            <div class="mt-4 flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/40 p-1.5 rounded-lg w-fit text-[9px] text-emerald-700 dark:text-emerald-400 font-bold uppercase">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span> 
                Internal LPSE
            </div>
        </div>

        {{-- Total Tamu --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Total Tamu</p>
                    <h3 class="text-4xl font-black text-slate-800 dark:text-slate-200 tracking-tighter">{{ $totalTamu }}</h3>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl text-2xl group-hover:bg-emerald-900 dark:group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-lg shadow-slate-100 dark:shadow-none">📖</div>
            </div>
            <div class="mt-4 flex items-center gap-2 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-lg w-fit text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase">
                Database SOWAN
            </div>
        </div>

        {{-- Tamu Hari Ini (Dinamis) --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Kunjungan Hari Ini</p>
                    <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">{{ $tamuHariIni }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-4 rounded-2xl text-2xl group-hover:rotate-12 transition-all duration-500">📍</div>
            </div>
            <p class="mt-4 text-[9px] text-emerald-400 font-bold uppercase italic tracking-wider">Real-time update</p>
        </div>

        {{-- Kepuasan Publik (Dinamis) --}}
        @php
            $ratingPercentage = ($avgRating / 5) * 100;
        @endphp
        <div class="group bg-emerald-900 dark:bg-emerald-950 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/20 dark:shadow-black/40 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-400">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-emerald-300 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Rating Layanan</p>
                    <h3 class="text-2xl font-black text-white tracking-tighter">{{ $avgRating }} / 5.0</h3>
                </div>
                <div class="text-4xl group-hover:scale-125 transition-transform duration-500">🤩</div>
            </div>
            <div class="w-full bg-emerald-800 dark:bg-slate-800 h-2 rounded-full mt-4 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-200 h-full transition-all duration-1000 ease-out" style="width: {{ $ratingPercentage }}%"></div>
            </div>
        </div>
    </div>

    {{-- 3. Baris Visualisasi Data --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 items-stretch">
        {{-- Grafik Tren Kunjungan --}}
        <div class="lg:col-span-2 flex">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-slate-50 dark:border-slate-800 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest flex items-center gap-2">
                        <span class="w-8 h-8 bg-emerald-900 dark:bg-emerald-500 text-white rounded-lg flex items-center justify-center text-xs">📈</span>
                        Analisis Mingguan
                    </h3>
                </div>
                <div class="flex-grow min-h-[350px]">
                    <canvas id="guestChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Statistik Tujuan --}}
        <div class="flex">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-slate-50 dark:border-slate-800 flex flex-col">
                <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest flex items-center gap-2 mb-8">
                    <span class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center text-xs">🎯</span>
                    Tujuan Utama
                </h3>
                
                <div class="flex-grow flex flex-col justify-center">
                    <div class="h-[220px] relative mb-10">
                        <canvas id="purposeChart"></canvas>
                    </div>
                    
                    <div class="space-y-4">
                        @php
                            $items = [
                                ['label' => 'Konsultasi LPSE', 'val' => 65, 'color' => '#059669'],
                                ['label' => 'Verifikasi Berkas', 'val' => 20, 'color' => '#10b981'],
                                ['label' => 'Urusan Lainnya', 'val' => 15, 'color' => '#d1fae5'],
                            ];
                        @endphp
                        @foreach($items as $item)
                        <div class="group">
                            <div class="flex justify-between items-center text-[10px] font-black mb-1.5">
                                <span class="text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $item['label'] }}</span>
                                <span class="text-emerald-700 dark:text-emerald-400">{{ $item['val'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-50 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-1000" style="width: {{ $item['val'] }}%; background-color: {{ $item['color'] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Footer Banner --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-800 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/20 border border-emerald-500/30">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">💡</div>
                <div>
                    <h4 class="text-xl font-black tracking-tight uppercase italic text-white">Navigasi Cepat</h4>
                    <p class="text-emerald-100/80 text-sm font-medium">Butuh perubahan data? Akses Manajemen User untuk mengatur petugas.</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-white dark:bg-emerald-400 text-emerald-900 dark:text-emerald-950 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl text-sm uppercase tracking-wider">
                Kelola User →
            </a>
        </div>
    </div>
</div>

{{-- 5. Scripts Visualisasi --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Deteksi Mode Gelap untuk warna Chart
    const isDark = document.documentElement.classList.contains('dark');
    const chartTextColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#1e293b' : '#f1f5f9';

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = chartTextColor;
    
    // Chart Tren (Line)
    const gCtx = document.getElementById('guestChart').getContext('2d');
    const gGrad = gCtx.createLinearGradient(0, 0, 0, 350);
    gGrad.addColorStop(0, isDark ? 'rgba(52, 211, 153, 0.2)' : 'rgba(16, 185, 129, 0.3)');
    gGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(gCtx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                data: [18, 32, 25, 45, 38, 12, 8],
                borderColor: '#10b981',
                backgroundColor: gGrad,
                fill: true,
                tension: 0.4,
                borderWidth: 6,
                pointBackgroundColor: isDark ? '#064e3b' : '#fff',
                pointBorderWidth: 4,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: gridColor }, ticks: { font: { weight: 'bold' } } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });

    // Chart Tujuan (Doughnut)
    const pCtx = document.getElementById('purposeChart').getContext('2d');
    new Chart(pCtx, {
        type: 'doughnut',
        data: {
            labels: ['Konsultasi', 'Verifikasi', 'Lainnya'],
            datasets: [{
                data: [65, 20, 15],
                backgroundColor: ['#059669', '#10b981', isDark ? '#064e3b' : '#d1fae5'],
                borderWidth: 8,
                borderColor: isDark ? '#0f172a' : '#ffffff',
                hoverOffset: 15
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script> 
@endsection