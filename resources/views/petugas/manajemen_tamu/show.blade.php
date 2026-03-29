@extends('layouts.app')

@section('title', 'Detail Kunjungan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter italic leading-none">
                Detail <span class="text-[#008f5d]">Kunjungan</span>
            </h1>
            <p class="text-slate-500 font-medium italic text-xs mt-2 tracking-wide">ID Registrasi: #{{ str_pad($loop->iteration ?? '1', 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <a href="{{ route('petugas.manajemen_tamu.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all border border-slate-200 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-emerald-50 shadow-xl text-center transform hover:scale-[1.02] transition-transform">
                <div class="w-24 h-24 bg-emerald-100 text-[#008f5d] rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-3xl font-black shadow-inner">
                    {{ substr($tamu->nama_tamu, 0, 1) }}
                </div>
                <h3 class="font-black text-slate-800 uppercase tracking-tight text-lg leading-tight">{{ $tamu->nama_tamu }}</h3>
                <div class="inline-block px-4 py-1.5 bg-emerald-50 text-[#008f5d] rounded-full text-[9px] font-black tracking-widest uppercase mt-3 border border-emerald-100">
                    {{ $tamu->nama_instansi }}
                </div>
            </div>

            <div class="bg-[#008f5d] p-7 rounded-[2.5rem] shadow-2xl shadow-emerald-900/20 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10 text-8xl transform rotate-12">
                    <i class="fas fa-id-badge"></i>
                </div>
                
                <label class="text-[9px] font-black text-emerald-200 uppercase tracking-[0.2em] block mb-2">Status Pelayanan</label>
                <p class="text-2xl font-black uppercase tracking-tighter italic flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                    </span>
                    {{ ucfirst($tamu->status ?? 'Belum') }} Dilayani
                </p>
                
                <hr class="my-6 border-emerald-400/30">
                
                <a href="{{ route('petugas.manajemen_tamu.edit', $tamu) }}" class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white text-white hover:text-[#008f5d] py-4 rounded-2xl transition-all duration-300 font-black text-[10px] uppercase tracking-[0.2em] shadow-lg">
                    <i class="fas fa-user-edit text-xs"></i> Perbarui Status
                </a>
            </div>
        </div>

        <div class="md:col-span-2 bg-white p-8 md:p-12 rounded-[2.5rem] border border-emerald-50 shadow-xl space-y-10 relative">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fas fa-envelope text-emerald-500"></i> Email / Gmail
                    </label>
                    <p class="font-black text-slate-700 text-lg tracking-tight lowercase">{{ $tamu->gmail }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fab fa-whatsapp text-emerald-500"></i> WhatsApp
                    </label>
                    <p class="font-black text-slate-700 text-lg tracking-tight">{{ $tamu->no_wa }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fas fa-user-tag text-emerald-500"></i> Hadir Sebagai
                    </label>
                    <p class="font-black text-slate-700 text-lg tracking-tight uppercase">{{ $tamu->hadir_sebagai }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-400"></i> Alamat Kantor
                    </label>
                    <p class="font-black text-slate-700 text-sm tracking-tight uppercase leading-snug">{{ $tamu->alamat_kantor ?? 'Tidak Diisi' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block italic">Maksud & Keperluan Kunjungan</label>
                <div class="bg-slate-50/80 p-8 rounded-[2rem] border-2 border-slate-50 italic font-bold text-slate-600 leading-relaxed text-base shadow-inner relative">
                    <i class="fas fa-quote-left absolute top-4 left-4 text-slate-200 text-xl"></i>
                    "{{ $tamu->keperluan ?? 'Tidak ada keterangan keperluan.' }}"
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <i class="fas fa-calendar-alt text-xs"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Kedatangan</span>
                        <span class="text-slate-800 font-black text-sm tracking-tighter">{{ $tamu->created_at->format('d F Y | H:i') }} WIB</span>
                    </div>
                </div>
                
                <div class="hidden sm:block text-right">
                    <span class="px-4 py-2 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-bold uppercase tracking-widest border border-slate-100">
                        SOWAN Digital Registry v2
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection