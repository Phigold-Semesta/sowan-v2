@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    
    <div class="relative overflow-hidden bg-white p-10 rounded-[2.5rem] shadow-sm border border-emerald-100 group">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl group-hover:bg-emerald-100 transition-colors"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">
                    Selamat Datang, <span class="text-[#008f5d]">{{ Auth::user()->nama_lengkap }}</span>! 👋
                </h2>
                <p class="text-slate-500 mt-2 font-medium">
                    Pantau aktivitas tamu dan layanan <span class="text-emerald-600 font-bold">LPSE Karawang</span> secara real-time.
                </p>
            </div>
            <div class="flex items-center gap-3 bg-emerald-50 px-6 py-3 rounded-2xl border border-emerald-100">
                <i class="fas fa-user-shield text-[#008f5d]"></i>
                <span class="text-xs font-bold text-[#1a4d3a] uppercase tracking-widest">{{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-gradient-to-br from-[#008f5d] to-[#006b45] p-8 rounded-[2.5rem] shadow-xl shadow-emerald-900/10 text-white relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
            <i class="fas fa-users absolute -right-4 -bottom-4 text-8xl text-white/10 group-hover:scale-110 transition-transform"></i>
            <p class="text-emerald-100/80 text-xs font-bold uppercase tracking-widest">Total Tamu Hari Ini</p>
            <h3 class="text-5xl font-black mt-3 tracking-tighter">12</h3>
            <div class="mt-4 flex items-center gap-2 text-xs font-bold bg-white/10 w-fit px-3 py-1 rounded-full">
                <i class="fas fa-arrow-up"></i> 15% dari kemarin
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100 relative overflow-hidden group hover:border-[#008f5d] transition-all">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-500 group-hover:text-white transition-all">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Sedang Dilayani</p>
            <h3 class="text-4xl font-black text-slate-800 mt-2 tracking-tighter">03</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100 relative overflow-hidden group hover:border-[#008f5d] transition-all">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <i class="fas fa-check-double"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Selesai / Pulang</p>
            <h3 class="text-4xl font-black text-slate-800 mt-2 tracking-tighter">09</h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100 relative overflow-hidden group hover:border-[#008f5d] transition-all">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-500 group-hover:text-white transition-all">
                <i class="fas fa-user-clock"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Antrean Menunggu</p>
            <h3 class="text-4xl font-black text-slate-800 mt-2 tracking-tighter">00</h3>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100 min-h-[300px]">
            <h4 class="text-lg font-black text-slate-800 uppercase tracking-tighter italic border-b border-slate-50 pb-4 mb-6">
                Aktivitas Terbaru
            </h4>
            <div class="flex items-center justify-center h-48 text-slate-300 italic text-sm">
                Belum ada data kunjungan terbaru untuk ditampilkan.
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-emerald-100">
            <h4 class="text-lg font-black text-slate-800 uppercase tracking-tighter italic border-b border-slate-50 pb-4 mb-6">
                Quick Actions
            </h4>
            <div class="space-y-3">
                <button class="w-full py-4 px-6 rounded-2xl bg-emerald-50 text-emerald-700 font-bold text-sm hover:bg-[#008f5d] hover:text-white transition-all flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i> Tambah Tamu Manual
                </button>
                <button class="w-full py-4 px-6 rounded-2xl bg-slate-50 text-slate-600 font-bold text-sm hover:bg-slate-800 hover:text-white transition-all flex items-center gap-3">
                    <i class="fas fa-print"></i> Cetak Laporan Hari Ini
                </button>
            </div>
        </div>
    </div>
</div>
@endsection