@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-emerald-100 flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h1>
            <p class="text-slate-500 mt-1 font-medium">Berikut adalah ringkasan aktivitas SOWAN hari ini.</p>
        </div>
        <i class="fas fa-leaf text-9xl text-emerald-50 absolute -right-4 -bottom-8 transform -rotate-12"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:border-emerald-300 transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Kunjungan</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">128</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-[10px] font-bold text-emerald-600 uppercase tracking-tight">
                <i class="fas fa-arrow-up mr-1"></i> 12% dari kemarin
            </div>
        </div>

        </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-black text-slate-800 uppercase tracking-tighter">Kunjungan Terbaru</h3>
            <a href="#" class="text-xs font-bold text-[#008f5d] hover:underline uppercase tracking-widest">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                </table>
        </div>
    </div>
</div>
@endsection