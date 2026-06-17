@extends('layouts.app_tamu')

@section('title', 'Dashboard Tamu')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-black text-emerald-900 dark:text-emerald-400 tracking-tighter uppercase italic">
                Selamat Datang
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 tracking-wide">
                {{-- PERBAIKAN: Menggunakan guard('tamu') agar tidak null --}}
                {{ auth()->guard('tamu')->user()->nama_tamu }}, portal SOWAN Anda siap digunakan.
            </p>
        </div>
        <div class="bg-white dark:bg-emerald-900/30 px-6 py-3 rounded-2xl border border-emerald-100 dark:border-emerald-800 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu Akses</p>
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-300">{{ now()->format('d M Y | H:i') }} WIB</p>
        </div>
    </div>

    {{-- Statistik Ringkas Tamu --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 rounded-[2rem] text-white shadow-xl">
            <p class="text-emerald-200 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Total Kunjungan</p>
            <h3 class="text-4xl font-black tracking-tighter">12 <span class="text-lg font-bold opacity-70">Kali</span></h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-lg">
            <p class="text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Kunjungan Terakhir</p>
            <h3 class="text-xl font-black text-slate-800 dark:text-slate-200 mt-1">15 Juni 2026</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-lg">
            <p class="text-slate-400 text-[10px] uppercase font-black tracking-[0.2em] mb-1">Status Akun</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-xl font-black text-emerald-600">TERVERIFIKASI</h3>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest mb-6">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('tamu.kunjungan.baru') }}" class="flex flex-col items-center justify-center p-6 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all border border-emerald-100 dark:border-emerald-800">
                    <i class="fas fa-plus text-2xl text-emerald-600 mb-3"></i>
                    <span class="text-xs font-bold text-emerald-900 dark:text-emerald-100">Kunjungan Baru</span>
                </a>
                <a href="{{ route('tamu.riwayat') }}" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-800 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all border border-slate-100 dark:border-slate-800">
                    <i class="fas fa-history text-2xl text-slate-500 mb-3"></i>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Riwayat</span>
                </a>
            </div>
        </div>

        <div class="bg-slate-900 dark:bg-emerald-950 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-black text-xl mb-2 italic uppercase">Perlu Bantuan?</h3>
                <p class="text-emerald-200 text-sm mb-6">Silakan hubungi operator LPSE Karawang melalui WhatsApp jika ada kendala sistem.</p>
                <a href="#" class="inline-block px-6 py-3 bg-emerald-500 text-white font-black text-xs uppercase rounded-xl hover:bg-emerald-400 transition-all shadow-lg">
                    Hubungi Admin →
                </a>
            </div>
            <i class="fas fa-headset absolute -bottom-6 -right-6 text-[150px] text-white/5 rotate-12"></i>
        </div>
    </div>
</div>
@endsection