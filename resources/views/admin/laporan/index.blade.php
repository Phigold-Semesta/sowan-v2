@extends('layouts.app')

@section('title', 'Laporan Kunjungan - SOWAN V2')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section: Mengikuti gaya Daftar Pengguna --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Laporan <span class="text-[#008f5d] dark:text-emerald-400">Kunjungan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Monitoring Data Kunjungan Tamu Real-Time
                </p>
            </div>
        </div>
        <form action="{{ route('admin.laporan.export') }}" method="GET">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="id_layanan" value="{{ request('id_layanan') }}">
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0">
                <i class="fas fa-file-export mr-2 group-hover:translate-y-[-2px] transition-transform duration-300"></i>
                Export Data Baru
            </button>
        </form>
    </div>

    {{-- Filter Section: Gaya Rounded-Pill Mewah --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Start Date --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-calendar text-sm"></i>
                </div>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all">
            </div>

            {{-- End Date --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-calendar-check text-sm"></i>
                </div>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                {{-- Layanan Filter --}}
                <div class="w-full md:w-64 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-filter text-xs"></i>
                    </div>
                    <select name="id_layanan" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">Semua Layanan</option>
                        @foreach($listLayanan as $layanan)
                            <option value="{{ $layanan->id_layanan }}" {{ request('id_layanan') == $layanan->id_layanan ? 'selected' : '' }}>
                                {{ strtoupper($layanan->nama_layanan) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <button type="submit" class="px-8 py-3.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section: Gaya Glassmorphism & Luxurious --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700 text-nowrap">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Waktu Masuk</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($kunjungan as $item)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-900/40 transition-all duration-200 group">
                        {{-- Profil Tamu --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <div class="p-0.5 rounded-[1.2rem] bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 shadow-sm group-hover:from-emerald-400 group-hover:to-emerald-200 transition-all duration-500">
                                        <div class="w-12 h-12 rounded-[1.1rem] border-2 border-white dark:border-slate-700 bg-[#008f5d] flex items-center justify-center text-white font-black text-sm group-hover:scale-105 transition-transform">
                                            {{ strtoupper(substr($item->tamu->nama_tamu ?? 'T', 0, 2)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate group-hover:text-[#008f5d] transition-colors">
                                        {{ $item->tamu->nama_tamu ?? '-' }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                        ID: <span class="text-slate-500 dark:text-slate-400">#{{ str_pad($item->id_kunjungan, 3, '0', STR_PAD_LEFT) }}</span>
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Waktu Masuk --}}
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center text-[11px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-3 py-1.5 rounded-xl border border-blue-100 dark:border-blue-800/30">
                                <i class="far fa-clock mr-1.5 opacity-70"></i>
                                {{ \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') }}
                            </span>
                        </td>

                        {{-- Layanan --}}
                        <td class="px-6 py-5">
                            <span class="text-[11px] font-black text-[#008f5d] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30 w-fit">
                                {{ strtoupper($item->layanan->nama_layanan ?? 'UMUM') }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusClasses = [
                                    'Belum Dilayani' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/20 dark:text-red-400',
                                    'Sedang Dilayani' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400',
                                    'Sudah Dilayani' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400',
                                ];
                                $class = $statusClasses[$item->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-xl border {{ $class }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-8 py-5 text-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all active:scale-90 shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                    <i class="fas fa-pen-nib text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100">
                                    <i class="fas fa-calendar-xmark text-4xl"></i>
                                </div>
                                <p class="text-sm font-black text-slate-400 uppercase tracking-widest">Tidak ada data kunjungan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kunjungan instanceof \Illuminate\Pagination\LengthAwarePaginator && $kunjungan->hasPages())
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d]"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                        Tampil {{ $kunjungan->firstItem() }} - {{ $kunjungan->lastItem() }} dari {{ $kunjungan->total() }} Kunjungan
                    </p>
                </div>
                <div class="custom-pagination">
                    {{ $kunjungan->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* SOWAN v2 Luxurious Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Tailwind Custom Pagination Overrides */
    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #008f5d !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
        box-shadow: 0 4px 12px rgba(0,143,93,0.2);
    }
    .custom-pagination nav a, .custom-pagination nav span {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .custom-pagination nav a:hover {
        background-color: #ecfdf5;
        color: #008f5d;
        transform: translateY(-2px);
    }
</style>
@endsection