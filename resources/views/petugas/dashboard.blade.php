@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 transition-colors duration-500 bg-emerald-50 dark:bg-slate-950 min-h-screen">
    {{-- 1. Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic drop-shadow-sm">
                Dashboard <span class="text-emerald-700 dark:text-white">Petugas</span>
            </h1>
            <p class="text-emerald-600/70 dark:text-slate-400 font-bold mt-1 tracking-wide">
                SOWAN V2 • Monitoring Kehadiran Tamu LPSE Karawang
                <span class="ml-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] rounded-full italic animate-pulse border border-emerald-200 dark:border-emerald-800">
                    ⚡ Helpdesk Aktif
                </span>
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black text-emerald-800/40 dark:text-slate-500 uppercase tracking-[0.3em]">Waktu Lokal</p>
            <p id="realtime-clock" class="text-sm font-bold text-emerald-900 dark:text-emerald-300">{{ now()->format('d M Y | H:i:s') }} WIB</p>
        </div>
    </div>

    {{-- 2. Baris Statistik Pelayanan (Emerald Theme) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Total Tamu Hari Ini --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border border-emerald-100 dark:border-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-800/50 dark:text-slate-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Tamu Hari Ini</p>
                    <h3 class="text-4xl font-black text-emerald-900 dark:text-emerald-100 tracking-tighter">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-lg shadow-emerald-100 dark:shadow-none">👥</div>
            </div>
            <div class="mt-4 flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/40 p-1.5 rounded-lg w-fit text-[9px] text-emerald-700 dark:text-emerald-400 font-bold uppercase">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span> 
                Update Real-time
            </div>
        </div>

        {{-- Belum Dilayani (Emerald-Gold Accent) --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-200 dark:border-emerald-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-600 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Belum Dilayani</p>
                    <h3 class="text-4xl font-black text-emerald-900 dark:text-slate-200 tracking-tighter">{{ $stats['belum'] ?? 0 }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-4 rounded-2xl text-2xl group-hover:rotate-12 transition-all duration-500 shadow-lg shadow-emerald-100 dark:shadow-none">⏳</div>
            </div>
            <p class="mt-4 text-[9px] text-emerald-400 font-bold uppercase italic tracking-wider">Menunggu Konfirmasi</p>
        </div>

        {{-- Sedang Dilayani (Emerald Light) --}}
        <div class="group bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-500 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Sedang Dilayani</p>
                    <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400 tracking-tighter">{{ $stats['sedang'] ?? 0 }}</h3>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-800/30 p-4 rounded-2xl text-2xl group-hover:scale-110 transition-all duration-500 shadow-lg shadow-emerald-100 dark:shadow-none">⚡</div>
            </div>
            <p class="mt-4 text-[9px] text-emerald-500 font-bold uppercase italic tracking-wider">Proses Helpdesk</p>
        </div>

        {{-- Selesai Dilayani (Emerald Dark - The Hero Card) --}}
        <div class="group bg-emerald-900 dark:bg-emerald-950 p-6 rounded-[2.5rem] shadow-2xl shadow-emerald-900/20 dark:shadow-black/40 transition-all duration-500 hover:-translate-y-2 border-b-4 border-emerald-400">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-emerald-300 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Selesai Dilayani</p>
                    <h3 class="text-4xl font-black text-white tracking-tighter">{{ $stats['sudah'] ?? 0 }}</h3>
                </div>
                <div class="text-4xl group-hover:scale-125 transition-transform duration-500">✅</div>
            </div>
            <p class="mt-4 text-[9px] text-emerald-400 font-bold uppercase italic tracking-wider">Pelayanan Tuntas</p>
        </div>
    </div>

    {{-- 3. Baris Visualisasi & Aksi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 items-stretch">
        {{-- Grafik Tren Kunjungan --}}
        <div class="lg:col-span-2 flex">
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-emerald-50 dark:border-slate-800 flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest flex items-center gap-2">
                        <span class="w-8 h-8 bg-emerald-900 dark:bg-emerald-500 text-white rounded-lg flex items-center justify-center text-xs">📈</span>
                        Analisis Kunjungan Mingguan
                    </h3>
                </div>
                <div class="flex-grow min-h-[350px]">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Menu & Logs --}}
        <div class="flex flex-col gap-6">
            {{-- Tombol Registrasi Manual --}}
            <div class="w-full bg-gradient-to-br from-emerald-700 to-emerald-950 p-8 rounded-[3rem] shadow-xl text-white flex flex-col justify-between group overflow-hidden relative border border-emerald-400/20 transition-all hover:scale-[1.02]">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black tracking-tighter italic uppercase mb-2 text-emerald-50">Registrasi Manual</h3>
                    <p class="text-emerald-200/80 text-xs font-medium mb-6">Tamu tidak bisa scan QR? Daftarkan langsung di sini.</p>
                    <a href="{{ route('petugas.manajemen_tamu.create') }}" class="inline-block px-8 py-4 bg-emerald-500 text-emerald-950 font-black rounded-2xl hover:bg-white active:scale-95 transition-all shadow-xl text-xs uppercase tracking-widest">
                        Buat Data Tamu →
                    </a>
                </div>
                <div class="absolute -right-5 -bottom-5 text-9xl opacity-10 group-hover:rotate-12 transition-transform duration-700">📝</div>
            </div>

            {{-- Audit Log Terakhir --}}
            <div class="w-full bg-white dark:bg-slate-900 p-8 rounded-[3rem] shadow-2xl border border-emerald-50 dark:border-slate-800 flex-grow">
                <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-50 dark:bg-slate-800 text-emerald-600 rounded-lg flex items-center justify-center text-xs">📜</span>
                    Log Aktivitas Anda
                </h3>
                <div class="space-y-4 overflow-y-auto max-h-[300px] pr-2">
                    @forelse($logs ?? [] as $log)
                    <div class="flex items-start gap-4 p-4 rounded-[1.5rem] hover:bg-emerald-50 dark:hover:bg-slate-800/50 transition-colors border border-transparent hover:border-emerald-100 dark:hover:border-slate-700">
                        <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                        <div class="flex-grow">
                            <p class="text-xs font-bold text-emerald-900 dark:text-slate-300 leading-tight mb-1">{{ $log->aktivitas }}</p>
                            <span class="text-[10px] text-emerald-600/50 dark:text-slate-400 font-black uppercase tracking-tighter">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-10 opacity-50">
                        <div class="text-4xl mb-2">🍃</div>
                        <p class="text-emerald-900 dark:text-slate-400 text-xs font-bold uppercase">Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Footer Banner --}}
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-800 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/40 border border-emerald-500/20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div class="flex flex-col md:flex-row items-center gap-5">
                <div class="w-16 h-16 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center text-3xl shadow-inner">📊</div>
                <div>
                    <h4 class="text-xl font-black tracking-tight uppercase italic text-emerald-50">Navigasi Operasional</h4>
                    <p class="text-emerald-300/80 text-sm font-medium italic">Kelola antrean tamu dan pantau laporan SOWAN secara real-time.</p>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('petugas.manajemen_tamu.index') }}" class="px-8 py-4 bg-white text-emerald-900 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl text-xs uppercase tracking-wider">
                    Monitor Tamu 👥
                </a>
                <a href="{{ route('petugas.laporan.index') }}" class="px-8 py-4 bg-emerald-400 text-emerald-950 font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl text-xs uppercase tracking-wider">
                    Lihat Laporan 📂
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Scripts Visualisasi --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Realtime Clock
    setInterval(() => {
        const now = new Date();
        const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('realtime-clock').textContent = now.toLocaleDateString('id-ID', options) + ' WIB';
    }, 1000);

    // Deteksi Mode Gelap & Chart Config
    const isDark = document.documentElement.classList.contains('dark');
    const chartTextColor = isDark ? '#94a3b8' : '#064e3b';
    const gridColor = isDark ? '#1e293b' : '#ecfdf5';

    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = chartTextColor;

    const gCtx = document.getElementById('visitorChart').getContext('2d');
    const gGrad = gCtx.createLinearGradient(0, 0, 0, 350);
    gGrad.addColorStop(0, isDark ? 'rgba(52, 211, 153, 0.2)' : 'rgba(16, 185, 129, 0.3)');
    gGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(gCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!},
            datasets: [{
                label: 'Kunjungan',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#10b981',
                backgroundColor: gGrad,
                fill: true,
                tension: 0.4,
                borderWidth: 6,
                pointBackgroundColor: isDark ? '#064e3b' : '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 4,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: gridColor }, 
                    ticks: { font: { weight: 'bold' }, beginAtZero: true } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { font: { weight: 'bold' } } 
                }
            }
        }
    });
</script>
@endsection