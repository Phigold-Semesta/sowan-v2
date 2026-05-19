@extends('layouts.app')

@section('title', 'Laporan Kunjungan - SOWAN V2 Petugas')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn" x-data="{ openExport: false }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Laporan <span class="text-[#008f5d] dark:text-emerald-400">Kunjungan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Panel Petugas: Monitoring Data Tamu LPSE
                </p>
            </div>
        </div>
        
        {{-- Dropdown Export Berbasis Alpine.js --}}
        <div class="relative inline-block text-left">
            <button @click="openExport = !openExport" @click.away="openExport = false" type="button"
                    class="inline-flex items-center px-6 py-3 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0">
                <i class="fas fa-file-export mr-2 group-hover:translate-y-[-2px] transition-transform duration-300"></i>
                Export Data
                <i class="fas fa-chevron-down ml-2 text-[10px] transition-transform duration-300" :class="openExport ? 'rotate-180' : ''"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="openExport" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-[-10px]"
                 class="absolute right-0 mt-3 w-56 origin-top-right bg-white dark:bg-slate-800 rounded-[1.5rem] shadow-2xl border border-emerald-50 dark:border-slate-700 z-50 overflow-hidden" 
                 style="display: none;">
                <div class="py-2">
                    @php 
                        $currentFilters = request()->only(['tgl_mulai', 'tgl_selesai', 'id_layanan', 'period', 'status']);
                    @endphp
                    
                    {{-- Export PDF --}}
                    <a href="{{ route('petugas.laporan.export', array_merge($currentFilters, ['format' => 'pdf'])) }}" 
                       class="flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600 transition-colors">
                        <i class="fas fa-file-pdf mr-3 text-lg text-red-500"></i>
                        Format PDF 
                    </a>

                    {{-- Export Excel --}}
                    <a href="{{ route('petugas.laporan.export', array_merge($currentFilters, ['format' => 'excel'])) }}" 
                       class="flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-[#008f5d] transition-colors">
                        <i class="fas fa-file-excel mr-3 text-lg text-emerald-500"></i>
                        Format Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="space-y-4">
        {{-- Quick Filter Buttons --}}
        <div class="flex flex-wrap items-center gap-3">
            @php $activePeriod = request('period'); @endphp
            @foreach(['today' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini'] as $key => $label)
            <a href="{{ route('petugas.laporan.index', ['period' => $key]) }}" 
               class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border {{ $activePeriod == $key ? 'bg-[#008f5d] text-white border-[#008f5d] shadow-lg shadow-emerald-500/20' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700 hover:border-[#008f5d] hover:text-[#008f5d]' }}">
                <i class="fas fa-calendar-{{ $key == 'today' ? 'day' : ($key == 'weekly' ? 'week' : 'alt') }} mr-2"></i> {{ $label }}
            </a>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-all duration-300">
            <form action="{{ route('petugas.laporan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
                <input type="hidden" name="period" value="{{ request('period') }}">
                
                <div class="w-full lg:flex-1 space-y-2 group">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-[#008f5d]">
                            <i class="fas fa-calendar text-sm"></i>
                        </div>
                        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 outline-none">
                    </div>
                </div>

                <div class="w-full lg:flex-1 space-y-2 group">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Akhir</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-[#008f5d]">
                            <i class="fas fa-calendar-check text-sm"></i>
                        </div>
                        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 outline-none">
                    </div>
                </div>

                <div class="w-full lg:w-64 space-y-2 group">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Layanan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-filter text-xs"></i>
                        </div>
                        <select name="id_layanan"
                                class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none outline-none">
                            <option value="">Semua Layanan</option>
                            @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id_layanan }}" {{ request('id_layanan') == $layanan->id_layanan ? 'selected' : '' }}>
                                    {{ strtoupper($layanan->nama_layanan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-8 py-3.5 bg-[#008f5d] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 transition-all duration-300 shadow-lg shadow-emerald-500/20 active:scale-95">
                        Terapkan
                    </button>
                    @if(request()->anyFilled(['tgl_mulai', 'tgl_selesai', 'id_layanan', 'period']))
                    <a href="{{ route('petugas.laporan.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center">
                        <i class="fas fa-undo text-xs"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Waktu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($kunjungans as $item)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-900/40 transition-all duration-200 group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[1.1rem] bg-[#008f5d] flex items-center justify-center text-white font-black text-sm border-2 border-white dark:border-slate-700 shadow-sm">
                                    {{ strtoupper(substr($item->tamu->nama_tamu ?? 'T', 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate group-hover:text-[#008f5d]">
                                        {{ $item->tamu->nama_tamu ?? '-' }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                        {{ $item->tamu->nama_instansi ?? 'Pribadi' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex flex-col text-[10px] font-black text-blue-600 dark:text-blue-400">
                                <span>{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y') }}</span>
                                <span class="opacity-70">{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') }} WIB</span>
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <span class="text-[11px] font-black text-[#008f5d] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                {{ strtoupper($item->layanan->nama_layanan ?? 'UMUM') }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-center">
                            @php
                                $statusClasses = [
                                    'belum dilayani' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/20 dark:text-red-400',
                                    'sedang dilayani' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400',
                                    'sudah dilayani' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400',
                                ];
                                $class = $statusClasses[strtolower($item->status)] ?? 'bg-slate-50 text-slate-600';
                            @endphp
                            <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-xl border {{ $class }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-sm font-black text-slate-400 uppercase tracking-widest">Tidak ada data ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kunjungans->hasPages())
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex justify-between items-center gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Halaman {{ $kunjungans->currentPage() }} dari {{ $kunjungans->lastPage() }}
                </p>
                <div class="custom-pagination">
                    {{ $kunjungans->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    .custom-pagination nav > div:first-child { display: none; }
</style>
@endsection