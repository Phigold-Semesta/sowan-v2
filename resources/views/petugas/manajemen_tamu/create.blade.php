@extends('layouts.app')

@section('title', 'Registrasi Tamu Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter italic">
                Registrasi <span class="text-[#008f5d]">Tamu</span>
            </h1>
            <p class="text-slate-500 font-medium italic text-sm mt-1">Input data kunjungan baru secara manual.</p>
        </div>
        <a href="{{ route('petugas.manajemen_tamu.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-emerald-900/5 border border-emerald-50 overflow-hidden">
        <form action="{{ route('petugas.manajemen_tamu.store') }}" method="POST" class="p-8 md:p-12 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] ml-1">Nama Lengkap Tamu</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <input type="text" name="nama" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-[#008f5d] focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 tracking-tight" placeholder="Contoh: Budi Santoso">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] ml-1">Asal Instansi / Perusahaan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-building text-sm"></i>
                        </div>
                        <input type="text" name="instansi" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-[#008f5d] focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 tracking-tight" placeholder="Contoh: PT. Maju Bersama">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] ml-1">Nomor WhatsApp</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fab fa-whatsapp text-base"></i>
                        </div>
                        <input type="number" name="telepon" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-[#008f5d] focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 tracking-tight" placeholder="08123456xxxx">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] ml-1">Tujuan Bertemu</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-id-badge text-sm"></i>
                        </div>
                        <select name="tujuan_bidang" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-[#008f5d] focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-700 appearance-none">
                            <option value="">Pilih Bidang/Tujuan</option>
                            <option value="Layanan Pengadaan">Layanan Pengadaan</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Pembinaan & Advokasi">Pembinaan & Advokasi</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] ml-1">Detail Keperluan</label>
                <div class="relative group">
                    <textarea name="keperluan" rows="3" required class="w-full p-6 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-[#008f5d] focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-700 placeholder:text-slate-300 tracking-tight" placeholder="Jelaskan keperluan kunjungan secara singkat..."></textarea>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-[#008f5d] hover:bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-[0.25em] text-sm shadow-xl shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-[0.98]">
                Simpan & Daftarkan Tamu <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </form>
    </div>
</div>
@endsection