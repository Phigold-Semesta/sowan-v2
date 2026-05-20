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
        
        {{-- Dropdown Export --}}
        <div class="relative inline-block text-left">
            <button @click="openExport = !openExport" @click.away="openExport = false" type="button"
                    class="inline-flex items-center px-6 py-3 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0">
                <i class="fas fa-file-export mr-2 group-hover:translate-y-[-2px] transition-transform duration-300"></i>
                Export Data
                <i class="fas fa-chevron-down ml-2 text-[10px] transition-transform duration-300" :class="openExport ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="openExport" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 rounded-[1.5rem] shadow-2xl border border-emerald-50 dark:border-slate-700 z-50 overflow-hidden" 
                 style="display: none;">
                <div class="py-2">
                    @php 
                        $exportFilters = request()->all();
                    @endphp
                    
                    <a href="{{ route('petugas.laporan.export', array_merge($exportFilters, ['format' => 'pdf'])) }}" 
                       class="flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <i class="fas fa-file-pdf mr-3 text-lg text-red-500"></i> Format PDF 
                    </a>
                    <a href="{{ route('petugas.laporan.export', array_merge($exportFilters, ['format' => 'excel'])) }}" 
                       class="flex items-center px-5 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-emerald-50 hover:text-[#008f5d] transition-colors">
                        <i class="fas fa-file-excel mr-3 text-lg text-[#008f5d]"></i> Format Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-all duration-300">
        <form action="{{ route('petugas.laporan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="w-full lg:flex-1 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 transition-all outline-none">
            </div>
            <div class="w-full lg:flex-1 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Akhir</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 transition-all outline-none">
            </div>
            <div class="w-full lg:w-64 space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Layanan</label>
                <select name="id_layanan" class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 outline-none cursor-pointer">
                    <option value="">Semua Layanan</option>
                    @foreach($layanans as $layanan)
                        <option value="{{ $layanan->id_layanan }}" {{ request('id_layanan') == $layanan->id_layanan ? 'selected' : '' }}>{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="flex-1 px-8 py-3.5 bg-[#008f5d] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 active:scale-95">Terapkan</button>
                @if(request()->anyFilled(['tgl_mulai', 'tgl_selesai', 'id_layanan']))
                <a href="{{ route('petugas.laporan.index') }}" class="px-5 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all"><i class="fas fa-undo"></i></a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden custom-scrollbar">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($kunjungans as $item)
                    <tr class="hover:bg-emerald-50/40 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight">{{ $item->tamu->nama_tamu ?? '-' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->tamu->nama_instansi ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5 text-[10px] font-black text-[#008f5d]">
                            {{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[10px] font-black px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-[#008f5d] dark:text-emerald-400 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                {{ strtoupper($item->layanan->nama_layanan ?? 'UMUM') }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[9px] font-black px-3 py-1.5 rounded-xl border {{ $item->status == 'Sudah Dilayani' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-10 py-6 border-t border-emerald-50 dark:border-slate-700 custom-pagination">
            {{ $kunjungans->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    
    /* Pagination Refinement - SOWAN V2 Style */
    .custom-pagination nav { display: flex; align-items: center; justify-content: flex-end; }
    .custom-pagination nav > div:first-child { display: none; }
    
    .custom-pagination nav span[aria-current="page"] > span { 
        background: #008f5d !important; 
        color: white !important; 
        border-radius: 12px !important; 
        font-weight: 900 !important;
        box-shadow: 0 4px 14px rgba(0, 143, 93, 0.3) !important;
    }
    
    .custom-pagination nav a, .custom-pagination nav span[aria-current="page"] > span {
        padding: 8px 16px !important;
        margin: 0 2px;
        border-radius: 12px !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .custom-pagination nav a:hover {
        background: #f1f5f9;
        color: #008f5d;
        border-color: #008f5d;
    }
</style>
@endsection