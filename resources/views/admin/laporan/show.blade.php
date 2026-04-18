@extends('layouts.app')

@section('title', 'Detail Kunjungan - SOWAN V2')

@section('content')
<div class="max-w-4xl mx-auto animate__animated animate__fadeInUp">
    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.laporan.index') }}" class="group flex items-center gap-3 text-slate-400 hover:text-[#008f5d] transition-all">
            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center group-hover:shadow-emerald-100 transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali ke Laporan</span>
        </a>
        <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
            <span class="text-[10px] font-black text-[#008f5d] dark:text-emerald-400 uppercase tracking-widest">ID Kunjungan: #{{ str_pad($kunjungan->id_kunjungan, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-xl shadow-emerald-900/5 overflow-hidden border border-emerald-50 dark:border-slate-700">
        {{-- Header Profile --}}
        <div class="relative h-32 bg-gradient-to-r from-[#008f5d] to-emerald-400">
            <div class="absolute -bottom-12 left-10">
                <div class="p-2 rounded-[2.5rem] bg-white dark:bg-slate-800">
                    <div class="w-24 h-24 rounded-[2rem] bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-3xl font-black text-[#008f5d] border-4 border-[#008f5d]/10">
                        {{ strtoupper(substr($kunjungan->tamu->nama_tamu ?? '??', 0, 2)) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-16 px-10 pb-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">{{ $kunjungan->tamu->nama_tamu }}</h1>
                    <p class="text-[#008f5d] font-bold uppercase tracking-widest text-xs mt-1">{{ $kunjungan->tamu->instansi_tamu ?? 'Personal / Umum' }}</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Layanan</span>
                    @php
                        $statusColor = match($kunjungan->status) {
                            'Sudah Dilayani' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                            'Sedang Dilayani' => 'bg-blue-100 text-blue-600 border-blue-200',
                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="mt-1 px-4 py-2 {{ $statusColor }} rounded-2xl text-[10px] font-black uppercase border">
                        {{ $kunjungan->status }}
                    </span>
                </div>
            </div>

            <hr class="my-8 border-slate-100 dark:border-slate-700">

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Layanan yang Dituju</label>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-[#008f5d]">
                                <i class="fas fa-concierge-bell text-xs"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $kunjungan->layanan->nama_layanan ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Waktu Kunjungan</label>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500">
                                <i class="fas fa-clock text-xs"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('d M Y | H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Petugas / Tujuan Spesifik</label>
                        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                            <p class="text-[10px] font-black text-[#008f5d] uppercase mb-1">{{ $kunjungan->petugas->nama_petugas ?? 'Umum' }}</p>
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-400 italic">
                                "{{ $kunjungan->perihal ?? 'Tidak ada deskripsi perihal' }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rating Section --}}
            @if($kunjungan->rating)
            <div class="mt-10 p-6 bg-amber-50 dark:bg-amber-900/10 rounded-[2rem] border border-amber-100 dark:border-amber-800/30">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">Feedback Tamu</label>
                    <span class="text-[9px] font-bold text-amber-500/50 uppercase italic">SOWAN V2 Verified</span>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    @for($i=1; $i<=5; $i++)
                        {{-- Perbaikan: Menggunakan 'skor' sesuai database --}}
                        <i class="fas fa-star {{ $i <= $kunjungan->rating->skor ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}"></i>
                    @endfor
                </div>
                <div class="bg-white/50 dark:bg-slate-900/40 p-4 rounded-xl">
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium italic leading-relaxed">
                        "{{ $kunjungan->rating->saran ?? 'Tamu tidak memberikan saran tambahan.' }}"
                    </p>
                </div>
            </div>
            @else
            <div class="mt-10 p-6 bg-slate-50 dark:bg-slate-900/30 rounded-[2rem] border border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada rating dari tamu</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection