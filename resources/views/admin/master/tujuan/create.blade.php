@extends('layouts.app')

@section('title', 'Tambah Tujuan Kunjungan')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic transition-colors">
                Tambah <span class="text-[#008f5d]">Tujuan</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.2em] mt-1 transition-colors">
                Daftarkan entitas tujuan kunjungan baru ke sistem SOWAN 🎯
            </p>
        </div>
        <a href="{{ route('admin.master.tujuan.index') }}" 
            class="flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-900/30 shadow-2xl shadow-emerald-100/50 dark:shadow-none overflow-hidden transition-all duration-500">
        <div class="p-8 md:p-12">
            
            {{-- Decoration Icon --}}
            <div class="flex justify-center mb-10">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-[1.5rem] rotate-3 flex items-center justify-center border-2 border-dashed border-emerald-200 dark:border-emerald-800 transition-colors">
                    <i class="fas fa-plus-circle text-3xl text-[#008f5d] -rotate-3"></i>
                </div>
            </div>

            {{-- Pastikan Route Store Sudah Benar --}}
            <form action="{{ route('admin.master.tujuan.tujuan_store') }}" method="POST" class="space-y-8">
                @csrf
                
                {{-- Nama Petugas / Unit --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1">
                        Nama Petugas / Nama Unit <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user-tag text-sm"></i>
                        </div>
                        <input type="text" name="nama_petugas" 
                            class="w-full pl-12 pr-5 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-slate-100 dark:border-slate-700 rounded-full text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-[#008f5d] focus:bg-white dark:focus:bg-slate-800 transition-all placeholder:text-slate-400 @error('nama_petugas') border-red-400 @enderror" 
                            placeholder="Contoh: Dede Kurniawan atau Unit Pelayanan"
                            value="{{ old('nama_petugas') }}" required autofocus>
                    </div>
                    @error('nama_petugas')
                        <p class="text-[10px] font-black text-red-500 uppercase italic ml-4 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jabatan / Bagian --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1">
                        Jabatan / Bagian <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <input type="text" name="jabatan" 
                            class="w-full pl-12 pr-5 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-slate-100 dark:border-slate-700 rounded-full text-sm font-bold text-slate-700 dark:text-white focus:outline-none focus:border-[#008f5d] focus:bg-white dark:focus:bg-slate-800 transition-all placeholder:text-slate-400 @error('jabatan') border-red-400 @enderror" 
                            placeholder="Contoh: Pengelola Pengadaan Barang/Jasa"
                            value="{{ old('jabatan') }}" required>
                    </div>
                    @error('jabatan')
                        <p class="text-[10px] font-black text-red-500 uppercase italic ml-4 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <button type="reset" 
                        class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 py-4 rounded-full text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                        <i class="fas fa-undo mr-2"></i> Reset Form
                    </button>
                    <button type="submit" 
                        class="flex-[2] bg-[#008f5d] text-white py-4 rounded-full text-[11px] font-black uppercase tracking-[0.2em] text-center hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 dark:hover:shadow-emerald-900/40 transition-all active:scale-[0.98] shadow-md group">
                        <i class="fas fa-save mr-2 group-hover:animate-pulse"></i> Simpan Data Tujuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection