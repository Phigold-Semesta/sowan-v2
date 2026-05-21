@extends('layouts.app')

@section('title', 'Laporan Kunjungan - SOWAN V2')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn" x-data="{ openExport: false }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Laporan <span class="text-[#008f5d]">Kunjungan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                    Monitoring Data Kunjungan Tamu Real-Time
                </p>
            </div>
        </div>
        
        {{-- Export Button --}}
        <div class="relative">
            <button @click="openExport = !openExport" @click.away="openExport = false" type="button"
                    class="inline-flex items-center px-6 py-3 bg-[#008f5d] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                <i class="fas fa-file-export mr-2"></i> Export Data
                <i class="fas fa-chevron-down ml-2 transition-transform" :class="openExport ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="openExport" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 rounded-[1.5rem] shadow-2xl border border-emerald-50 dark:border-slate-700 z-50 overflow-hidden" 
                 style="display: none;">
                <div class="py-2">
                    @php $currentFilters = request()->all(); @endphp
                    <a href="{{ route('pimpinan.laporan.export', array_merge($currentFilters, ['format' => 'csv'])) }}" class="flex items-center px-5 py-3 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 transition-colors"><i class="fas fa-file-csv mr-3 text-emerald-500"></i> Format CSV</a>
                    <a href="{{ route('pimpinan.laporan.export', array_merge($currentFilters, ['format' => 'pdf'])) }}" class="flex items-center px-5 py-3 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 transition-colors"><i class="fas fa-file-pdf mr-3 text-red-500"></i> Format PDF</a>
                    <a href="{{ route('pimpinan.laporan.export', array_merge($currentFilters, ['format' => 'excel'])) }}" class="flex items-center px-5 py-3 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 transition-colors"><i class="fas fa-file-excel mr-3 text-emerald-600"></i> Format Excel</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm space-y-6">
        <div class="flex flex-wrap gap-3">
            @foreach(['hari_ini' => 'Hari Ini', 'minggu_ini' => 'Minggu Ini', 'bulan_ini' => 'Bulan Ini', 'tahun_ini' => 'Tahun Ini'] as $val => $label)
            <a href="{{ route('pimpinan.laporan.index', ['range' => $val]) }}" class="px-5 py-2.5 {{ request('range') == $val ? 'bg-[#008f5d] text-white' : 'bg-slate-50 text-slate-600' }} border border-slate-100 text-[10px] font-black uppercase rounded-2xl hover:bg-emerald-600 hover:text-white transition-all">
                {{ $label }}
            </a>
            @endforeach
        </div>

        <form action="{{ route('pimpinan.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end pt-4 border-t border-slate-50">
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]">
            </div>
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]">
            </div>
            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Jenis Layanan</label>
                <select name="id_layanan" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-0 rounded-2xl text-[10px] font-black uppercase cursor-pointer focus:ring-2 focus:ring-[#008f5d]">
                    <option value="">Semua Layanan</option>
                    @foreach($listLayanan as $layanan)
                        <option value="{{ $layanan->id_layanan }}" {{ request('id_layanan') == $layanan->id_layanan ? 'selected' : '' }}>{{ $layanan->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-3.5 bg-[#008f5d] text-white text-[10px] font-black uppercase rounded-2xl hover:bg-emerald-700 transition-all">Terapkan</button>
                <a href="{{ route('pimpinan.laporan.index') }}" class="px-5 py-3.5 bg-slate-100 text-slate-500 rounded-2xl hover:bg-red-50 hover:text-red-600 transition-all"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-emerald-50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50">
                    @forelse($kunjungan as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-slate-800 uppercase">{{ $item->tamu->nama_tamu ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">ID: #{{ $item->id_kunjungan }}</p>
                        </td>
                        <td class="px-6 py-5 text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($item->waktu_masuk)->format('d M, H:i') }}</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-emerald-100 text-[#008f5d] text-[10px] font-black rounded-lg">{{ $item->layanan->nama_layanan ?? 'UMUM' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-emerald-50 text-[#008f5d] text-[9px] font-black uppercase rounded-lg border border-emerald-100">{{ $item->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-20 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Data Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-10 py-6 border-t border-emerald-50">
            {{ $kunjungan->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d; border-radius: 10px; }
</style>
@endsection