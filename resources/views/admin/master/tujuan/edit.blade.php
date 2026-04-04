@extends('layouts.app')

@section('title', 'Edit Tujuan Kunjungan - ' . $tujuan->nama_petugas)

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section - Mengikuti gaya referensi --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Edit <span class="text-[#008f5d] dark:text-emerald-400">Tujuan</span>
            </h1>
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                Perbarui informasi petugas/unit <span class="text-slate-600 dark:text-slate-300 italic font-black">"{{ $tujuan->nama_petugas }}"</span>
            </p>
        </div>
        
        {{-- Tombol Kembali gaya pill-shaped --}}
        <a href="{{ route('admin.master.tujuan.index') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-widest rounded-full shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all active:scale-95">
            <i class="fas fa-arrow-left mr-2.5 text-[#008f5d]"></i>
            Kembali
        </a>
    </div>

    {{-- Main Form Card - rounded-[2.5rem] mewah --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-emerald-50 dark:border-slate-700 transition-colors duration-300">
        <form action="{{ route('admin.master.tujuan.update', $tujuan->id_petugas) }}" method="POST" class="space-y-10">
            @csrf
            @method('PUT')

            {{-- Grid Input --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                
                {{-- Input Nama Petugas / Unit --}}
                <div class="relative group">
                    <label for="nama_petugas" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3 ml-1">
                        Nama Petugas / Unit
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user-tie text-sm"></i>
                        </div>
                        <input type="text" 
                               name="nama_petugas" 
                               id="nama_petugas" 
                               value="{{ old('nama_petugas', $tujuan->nama_petugas) }}"
                               placeholder="Contoh: Dede Kurniawan / Ruang LPSE"
                               class="w-full pl-14 pr-6 py-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d]/20 focus:border-[#008f5d] transition-all placeholder:text-slate-300 dark:placeholder:text-slate-600 @error('nama_petugas') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                    </div>
                    @error('nama_petugas')
                        <p class="text-xs text-red-500 font-bold mt-2 ml-1 animate__animated animate__headShake">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Jabatan / Bagian --}}
                <div class="relative group">
                    <label for="jabatan" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3 ml-1">
                        Jabatan / Bagian
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <input type="text" 
                               name="jabatan" 
                               id="jabatan" 
                               value="{{ old('jabatan', $tujuan->jabatan) }}"
                               placeholder="Contoh: Helpdesk / Verifikator"
                               class="w-full pl-14 pr-6 py-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d]/20 focus:border-[#008f5d] transition-all placeholder:text-slate-300 dark:placeholder:text-slate-600 @error('jabatan') border-red-300 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                    </div>
                    @error('jabatan')
                        <p class="text-xs text-red-500 font-bold mt-2 ml-1 animate__animated animate__headShake">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Action Buttons - Gaya pill-shaped --}}
            <div class="flex flex-col md:flex-row items-center justify-end gap-4 pt-8 border-t border-slate-100 dark:border-slate-700/50">
                <a href="{{ route('admin.master.tujuan.index') }}" 
                   class="w-full md:w-auto text-center px-8 py-3.5 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-xs font-black uppercase tracking-widest rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full md:w-auto px-10 py-3.5 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-emerald-700 dark:hover:bg-emerald-500 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all active:scale-95">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    {{-- Custom animation speed --}}
    .animate__animated {
        --animate-duration: 0.6s;
    }
    
    {{-- Smooth focus transition --}}
    input:focus {
        outline: none;
    }
</style>
@endsection