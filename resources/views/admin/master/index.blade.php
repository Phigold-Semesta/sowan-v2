@extends('layouts.app')

@section('title', 'Master Data')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Master <span class="text-sowan-emerald">Data</span>
            </h1>
            <p class="text-sm font-medium text-slate-500 dark:text-emerald-400/60">
                Konfigurasi parameter utama untuk alur kunjungan tamu LPSE.
            </p>
        </div>
        
        <div class="px-4 py-2 bg-white dark:bg-emerald-900/50 border border-emerald-100 dark:border-emerald-800 rounded-2xl shadow-sm">
            <span class="text-[10px] font-black text-slate-400 dark:text-emerald-500 uppercase tracking-widest block">Status Sistem</span>
            <span class="text-md font-bold text-sowan-emerald">Ready to Sync</span>
        </div>
    </div>

    {{-- Grid Menu Master Data - 2 Kolom Besar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- Card 1: Kategori Layanan --}}
        <a href="{{ route('admin.master.layanan.index') }}" class="group relative bg-white dark:bg-emerald-900 p-8 rounded-[3rem] border border-emerald-50 dark:border-emerald-800 shadow-xl shadow-emerald-900/5 hover:shadow-sowan-emerald/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-10 group-hover:scale-125 transition-transform duration-500 text-slate-800 dark:text-white">
                <i class="fas fa-concierge-bell text-8xl"></i>
            </div>
            
            <div class="relative z-10 space-y-6">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-800 rounded-3xl flex items-center justify-center text-sowan-emerald dark:text-emerald-300 group-hover:bg-sowan-emerald group-hover:text-white transition-colors duration-500 shadow-inner">
                    <i class="fas fa-list-ul text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Kategori Layanan</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-emerald-400/70 mt-2">Kelola daftar layanan utama (Verifikasi, Konsultasi, dll) yang akan tampil di form tamu.</p>
                </div>
                <div class="pt-4 flex items-center text-xs font-black tracking-widest text-sowan-emerald dark:text-emerald-400 uppercase">
                    Buka Pengaturan <i class="fas fa-arrow-right ml-2 group-hover:translate-x-3 transition-transform"></i>
                </div>
            </div>
        </a>

        {{-- Card 2: Tujuan Kunjungan --}}
        <a href="{{ route('admin.master.tujuan.index') }}" class="group relative bg-white dark:bg-emerald-900 p-8 rounded-[3rem] border border-emerald-50 dark:border-emerald-800 shadow-xl shadow-emerald-900/5 hover:shadow-sowan-emerald/30 transition-all duration-500 hover:-translate-y-2 overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-10 group-hover:scale-125 transition-transform duration-500 text-slate-800 dark:text-white">
                <i class="fas fa-bullseye text-8xl"></i>
            </div>
            
            <div class="relative z-10 space-y-6">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-800 rounded-3xl flex items-center justify-center text-sowan-emerald dark:text-emerald-300 group-hover:bg-sowan-emerald group-hover:text-white transition-colors duration-500 shadow-inner">
                    <i class="fas fa-map-marker-alt text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Tujuan Kunjungan</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-emerald-400/70 mt-2">Kelola alasan spesifik kedatangan tamu untuk pendataan statistik yang lebih akurat.</p>
                </div>
                <div class="pt-4 flex items-center text-xs font-black tracking-widest text-sowan-emerald dark:text-emerald-400 uppercase">
                    Buka Pengaturan <i class="fas fa-arrow-right ml-2 group-hover:translate-x-3 transition-transform"></i>
                </div>
            </div>
        </a>

    </div>

    {{-- Info Box --}}
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 dark:from-emerald-800 dark:to-emerald-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex items-center gap-6">
            <div class="p-4 bg-white/20 backdrop-blur-md rounded-2xl border border-white/30 hidden md:block">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-lg">Catatan Administrator</h4>
                <p class="text-emerald-50 text-sm opacity-90 leading-relaxed">
                    Data Instansi dan Bagian Intern sekarang dikelola secara otomatis oleh sistem untuk menyederhanakan alur kerja petugas LPSE Karawang.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection