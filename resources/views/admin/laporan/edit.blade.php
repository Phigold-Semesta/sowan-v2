@extends('layouts.app')

@section('title', 'Edit Kunjungan - SOWAN V2')

@section('content')
<div class="max-w-3xl mx-auto animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
            Edit Data <span class="text-[#008f5d]">Kunjungan</span>
        </h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Koreksi informasi kunjungan tamu secara mendalam</p>
    </div>

    {{-- Form Section --}}
    <form action="{{ route('admin.laporan.update', $kunjungan->id_kunjungan) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-10 shadow-xl border border-emerald-50 dark:border-slate-700 space-y-6">
            
            {{-- Input Nama Tamu (Read Only / Locked) --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2 tracking-widest">Identitas Tamu</label>
                <div class="relative">
                    <input type="text" value="{{ $kunjungan->tamu->nama_tamu ?? 'Tamu Umum' }} ({{ $kunjungan->tamu->email ?? '-' }})" disabled
                           class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border-0 text-sm font-bold text-slate-400 cursor-not-allowed">
                    <i class="fas fa-lock absolute right-6 top-1/2 -translate-y-1/2 text-slate-300"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pilih Layanan --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#008f5d] uppercase ml-2 tracking-widest">Kategori Layanan</label>
                    <div class="relative">
                        <select name="id_layanan" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#008f5d] dark:text-white appearance-none cursor-pointer outline-none">
                            @foreach($layanan as $l)
                                <option value="{{ $l->id_layanan }}" {{ (old('id_layanan', $kunjungan->id_layanan) == $l->id_layanan) ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-[#008f5d] pointer-events-none"></i>
                    </div>
                </div>

                {{-- Status Layanan --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-[#008f5d] uppercase ml-2 tracking-widest">Status Pelayanan</label>
                    <div class="relative">
                        {{-- Sesuai validasi Controller: 'Belum Dilayani', 'Sedang Dilayani', 'Sudah Dilayani' --}}
                        <select name="status" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#008f5d] dark:text-white appearance-none cursor-pointer outline-none">
                            @foreach(['Belum Dilayani', 'Sedang Dilayani', 'Sudah Dilayani'] as $st)
                                <option value="{{ $st }}" {{ (old('status', $kunjungan->status) == $st) ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-[#008f5d] pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- Input Petugas Tujuan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-[#008f5d] uppercase ml-2 tracking-widest">Petugas yang Ditemui</label>
                <div class="relative">
                    <select name="id_petugas" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#008f5d] dark:text-white appearance-none cursor-pointer outline-none">
                        @foreach($petugas as $p)
                            <option value="{{ $p->id_petugas }}" {{ (old('id_petugas', $kunjungan->id_petugas) == $p->id_petugas) ? 'selected' : '' }}>
                                {{ $p->nama_petugas }} ({{ $p->jabatan }})
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-[#008f5d] pointer-events-none"></i>
                </div>
            </div>

            {{-- Perihal / Tujuan Kunjungan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-[#008f5d] uppercase ml-2 tracking-widest">Perihal / Tujuan</label>
                <textarea name="perihal" rows="4" required
                          class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#008f5d] dark:text-white outline-none transition-all placeholder:text-slate-300"
                          placeholder="Masukkan perihal kunjungan...">{{ old('perihal', $kunjungan->perihal) }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-4 pt-6">
                <a href="{{ route('admin.laporan.index') }}" 
                   class="flex-1 px-8 py-4 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-2xl text-center hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button type="submit" 
                        class="flex-[2] px-8 py-4 bg-[#008f5d] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-600 shadow-lg shadow-emerald-900/20 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan Data
                </button>
            </div>
        </div>
    </form>
</div>
@endsection