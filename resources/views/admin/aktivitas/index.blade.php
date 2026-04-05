@extends('layouts.app')

@section('title', 'Audit Log Sistem')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Log <span class="text-[#008f5d] dark:text-emerald-400">Aktivitas</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Rekaman jejak audit dan keamanan sistem SOWAN v2
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 shrink-0">
            <div class="px-5 py-3 bg-white dark:bg-slate-800 border border-emerald-50 dark:border-slate-700 rounded-2xl shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Log</p>
                <p class="text-lg font-black text-[#008f5d] dark:text-emerald-400 mt-1">
                    {{ is_countable($activities) ? number_format(count($activities)) : number_format($activities->total()) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.aktivitas.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari aktivitas atau nama user..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3 items-center">
                {{-- Per Page Filter --}}
                <div class="relative min-w-[140px]">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-list-ol text-xs"></i>
                    </div>
                    <select name="per_page" class="w-full pl-10 pr-8 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>

                {{-- Date Filter --}}
                <div class="w-full md:w-48 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-calendar-alt text-xs"></i>
                    </div>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="w-full pl-10 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200">
                </div>

                <button type="submit" class="px-6 py-3.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all active:scale-95 shadow-sm">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700 text-nowrap">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Timestamp</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Pelaku (User)</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Aktivitas</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Alamat IP</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Status Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($activities as $log)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-900/40 transition-all duration-200 group text-nowrap">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight italic">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    {{ $log->created_at->format('H:i:s') }} WIB
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->nama_lengkap ?? 'System') }}&background=008f5d&color=fff&bold=true" 
                                     class="w-8 h-8 rounded-xl border border-white dark:border-slate-700 shadow-sm">
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase truncate">
                                        {{ $log->user->nama_lengkap ?? 'System' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-[#008f5d] dark:text-emerald-500 uppercase tracking-widest">
                                        {{ $log->user->role ?? 'Sistem Otomatis' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 tracking-tight">
                                    {{ $log->deskripsi }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[10px] font-black font-mono text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-900/50 px-2 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                {{ $log->ip_address }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                <i class="fas fa-shield-alt mr-1.5 opacity-70"></i>
                                TERVERIFIKASI
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100 dark:border-slate-800">
                                    <i class="fas fa-history text-3xl"></i>
                                </div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Belum ada rekaman aktivitas ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination (Hanya tampil jika bukan mode 'Semua') --}}
        @if(request('per_page') !== 'all' && $activities instanceof \Illuminate\Pagination\LengthAwarePaginator && $activities->hasPages())
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d] dark:bg-emerald-500"></div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">
                        Tampil {{ $activities->firstItem() }} - {{ $activities->lastItem() }} dari {{ $activities->total() }} Log
                    </p>
                </div>
                <div class="custom-pagination">
                    {{ $activities->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Custom Select Styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }

    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #008f5d !important;
        border-color: #008f5d !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
    }
    .custom-pagination nav a, .custom-pagination nav span {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        border: none !important;
        background-color: #ffffff;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .dark .custom-pagination nav a, .dark .custom-pagination nav span:not([aria-current="page"] > span) {
        background-color: #0f172a;
        color: #94a3b8;
    }
</style>
@endsection