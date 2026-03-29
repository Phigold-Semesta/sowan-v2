@extends('layouts.app')

@section('title', 'Manajemen Tamu')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tighter italic">
                Data Kunjungan <span class="text-[#008f5d]">SOWAN v2</span>
            </h1>
            <p class="text-slate-500 mt-1 font-medium text-sm italic">
                Kelola status pelayanan tamu UKPBJ Karawang hari ini.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.manajemen_tamu.create') }}" class="px-6 py-3.5 bg-[#008f5d] hover:bg-emerald-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all flex items-center gap-2 transform hover:-translate-y-1">
                <i class="fas fa-plus-circle"></i> Tambah Tamu Manual
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-[#008f5d] p-4 rounded-xl flex items-center gap-3 animate-pulse">
        <i class="fas fa-check-circle text-[#008f5d]"></i>
        <p class="text-emerald-800 font-bold text-xs uppercase tracking-widest">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#f0f9f4] border-b border-emerald-50">
                    <tr class="text-slate-800 font-bold text-[10px] uppercase tracking-[0.2em]">
                        <th class="px-6 py-6 text-center w-16">No</th>
                        <th class="px-6 py-6 italic">Informasi Tamu</th>
                        <th class="px-6 py-6 italic">Instansi / Tujuan</th>
                        <th class="px-6 py-6 text-center italic">Status Layanan</th>
                        <th class="px-6 py-6 text-center italic">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tamus as $tamu)
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-6 py-6 text-center font-black text-slate-300 group-hover:text-emerald-500 transition-colors">
                            {{ ($tamus->currentPage() - 1) * $tamus->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-800 uppercase tracking-tight text-base leading-none">
                                    {{ $tamu->nama_tamu }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-envelope text-[8px]"></i> {{ $tamu->gmail }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700 uppercase text-xs tracking-wide">
                                    {{ $tamu->nama_instansi }}
                                </span>
                                <span class="text-[9px] font-black text-emerald-600/60 uppercase mt-1">
                                    Hadir Sebagai: {{ $tamu->hadir_sebagai }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $statusStyles = [
                                    'belum'  => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'sedang' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'sudah'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                ];
                                $currentStatus = strtolower($tamu->status ?? 'belum');
                            @endphp
                            <span class="inline-flex px-3 py-1.5 rounded-xl border {{ $statusStyles[$currentStatus] ?? $statusStyles['belum'] }} font-black text-[9px] uppercase tracking-[0.15em] shadow-sm">
                                <i class="fas fa-circle text-[6px] mr-2 self-center animate-pulse"></i>
                                {{ ucfirst($tamu->status ?? 'Belum') }} Dilayani
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex justify-center items-center gap-3">
                                {{-- Tombol Show: Warna Kuning --}}
                                <a href="{{ route('petugas.manajemen_tamu.show', $tamu) }}" 
                                   class="w-11 h-11 flex items-center justify-center rounded-2xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all shadow-sm border border-amber-100 transform hover:scale-110" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                {{-- Tombol Edit: Warna Biru Muda --}}
                                <a href="{{ route('petugas.manajemen_tamu.edit', $tamu) }}" 
                                   class="w-11 h-11 flex items-center justify-center rounded-2xl bg-sky-50 text-sky-500 hover:bg-sky-500 hover:text-white transition-all shadow-sm border border-sky-100 transform hover:scale-110" 
                                   title="Ubah Status">
                                    <i class="fas fa-user-edit text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                    <i class="fas fa-user-slash text-slate-200 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.3em]">Belum ada data kunjungan hari ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tamus->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $tamus->links() }}
        </div>
        @endif
    </div>
</div>
@endsection