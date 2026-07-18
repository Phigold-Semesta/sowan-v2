@extends('layouts.app_tamu') {{-- Sesuaikan dengan nama layout sidebar Anda --}}

@section('title', 'Riwayat Kunjungan')

@section('content')
<div class="max-w-7xl mx-auto animate__animated animate__fadeIn">
    <!-- Header Section -->
    <div class="bg-white dark:bg-emerald-900 rounded-[2.5rem] p-8 shadow-sm border border-emerald-100 dark:border-emerald-800 mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-emerald-950 dark:text-white uppercase italic tracking-tighter">
                Riwayat <span class="text-[#008f5d] dark:text-emerald-400">Kunjungan</span>
            </h1>
            <p class="text-emerald-600 dark:text-emerald-300 mt-2 font-bold text-xs md:text-sm uppercase tracking-widest">
                Rekam jejak presensi dan layanan Anda di LPSE Karawang.
            </p>
        </div>
        <div class="hidden md:flex p-5 bg-emerald-50 dark:bg-emerald-800 rounded-3xl border border-emerald-100 dark:border-emerald-700 shadow-sm">
            <i class="fas fa-history text-4xl text-[#008f5d] dark:text-emerald-400"></i>
        </div>
    </div>

    <!-- Table Section: Bayangan diperbaiki agar tidak tebal -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-[0.2em] font-black">
                        <th class="p-6">Waktu Masuk</th>
                        <th class="p-6 text-center">No. Antrean</th>
                        <th class="p-6">ID Layanan</th>
                        <th class="p-6">ID Petugas</th>
                        <th class="p-6 text-center">Status Layanan</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-slate-700 dark:text-slate-200">
                    @forelse($riwayatKunjungan as $riwayat)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-emerald-50/30 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-6">
                            <span class="block text-emerald-900 dark:text-white">{{ \Carbon\Carbon::parse($riwayat->waktu_masuk)->format('d M Y') }}</span>
                            <span class="text-[10px] text-emerald-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($riwayat->waktu_masuk)->format('H:i') }} WIB</span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="inline-block bg-emerald-50 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 px-4 py-2 rounded-xl font-black text-lg border border-emerald-100 dark:border-emerald-800">
                                {{ str_pad($riwayat->nomor_antrean, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="text-[10px] uppercase tracking-wider text-slate-400">Layanan ID:</span><br>
                            {{ $riwayat->id_layanan }}
                        </td>
                        <td class="p-6">
                            <span class="text-[10px] uppercase tracking-wider text-slate-400">Petugas ID:</span><br>
                            {{ $riwayat->id_petugas }}
                        </td>
                        <td class="p-6 text-center">
                            @if(strtolower($riwayat->status) == 'sudah dilayani')
                                <span class="bg-emerald-100 text-[#008f5d] border border-emerald-200 px-4 py-2 rounded-full text-[10px] uppercase tracking-widest font-black shadow-sm">Sudah Dilayani</span>
                            @elseif(strtolower($riwayat->status) == 'sedang dilayani')
                                <span class="bg-amber-100 text-amber-700 border border-amber-200 px-4 py-2 rounded-full text-[10px] uppercase tracking-widest font-black shadow-sm">Sedang Dilayani</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-4 py-2 rounded-full text-[10px] uppercase tracking-widest font-black shadow-sm">{{ $riwayat->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center border-t border-slate-100 dark:border-slate-700">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-folder-open text-4xl mb-4 opacity-50"></i>
                                <p class="text-xs font-black uppercase tracking-widest">Belum ada riwayat kunjungan tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection