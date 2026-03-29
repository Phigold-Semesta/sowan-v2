@extends('layouts.app')

@section('title', 'Edit Status Tamu')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter italic leading-none">
                Update <span class="text-[#008f5d]">Status</span>
            </h1>
            <p class="text-slate-500 font-medium italic text-sm mt-2">
                Perbarui pelayanan untuk: <span class="text-slate-800 font-bold">{{ $tamu->nama_tamu }}</span>
            </p>
        </div>
        <a href="{{ route('petugas.manajemen_tamu.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2 border border-slate-200">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 overflow-hidden">
        <form action="{{ route('petugas.manajemen_tamu.update', $tamu) }}" method="POST" class="p-8 md:p-12 space-y-10">
            @csrf
            @method('PUT')
            
            <div class="bg-slate-50/80 p-8 rounded-[2rem] border border-slate-100">
                <label class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-6 block text-center">
                    Pilih Status Pelayanan Saat Ini
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative group cursor-pointer">
                        <input type="radio" name="status" value="belum" class="absolute opacity-0 peer" {{ strtolower($tamu->status) == 'belum' ? 'checked' : '' }}>
                        <div class="p-5 bg-white rounded-2xl border-2 transition-all duration-300 group-hover:shadow-md border-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                                    <i class="fas fa-clock text-xs"></i>
                                </div>
                                <span class="font-black text-[10px] uppercase tracking-widest text-amber-700 mb-1">Belum</span>
                                <span class="text-[9px] font-bold text-slate-400 italic">Menunggu antrean</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative group cursor-pointer">
                        <input type="radio" name="status" value="sedang" class="absolute opacity-0 peer" {{ strtolower($tamu->status) == 'sedang' ? 'checked' : '' }}>
                        <div class="p-5 bg-white rounded-2xl border-2 transition-all duration-300 group-hover:shadow-md border-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3 animate-pulse">
                                    <i class="fas fa-spinner text-xs"></i>
                                </div>
                                <span class="font-black text-[10px] uppercase tracking-widest text-blue-700 mb-1">Sedang</span>
                                <span class="text-[9px] font-bold text-slate-400 italic">Dalam pelayanan</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative group cursor-pointer">
                        <input type="radio" name="status" value="sudah" class="absolute opacity-0 peer" {{ strtolower($tamu->status) == 'sudah' ? 'checked' : '' }}>
                        <div class="p-5 bg-white rounded-2xl border-2 transition-all duration-300 group-hover:shadow-md border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#008f5d] flex items-center justify-center mb-3">
                                    <i class="fas fa-check-circle text-xs"></i>
                                </div>
                                <span class="font-black text-[10px] uppercase tracking-widest text-emerald-700 mb-1">Sudah</span>
                                <span class="text-[9px] font-bold text-slate-400 italic">Selesai dilayani</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1 flex items-center gap-2">
                        <i class="fas fa-user text-[9px]"></i> Nama Lengkap Tamu
                    </label>
                    <input type="text" value="{{ $tamu->nama_tamu }}" 
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-500 outline-none cursor-not-allowed" disabled>
                    <p class="text-[9px] text-slate-400 italic ml-1">*Nama tamu tidak dapat diubah di halaman ini</p>
                </div>
                
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1 flex items-center gap-2">
                        <i class="fas fa-building text-[9px]"></i> Asal Instansi
                    </label>
                    <input type="text" value="{{ $tamu->nama_instansi }}" 
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-500 outline-none cursor-not-allowed" disabled>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-[#008f5d] hover:bg-emerald-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.25em] text-xs shadow-xl shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-3">
                    Simpan Perubahan Status <i class="fas fa-check-double text-sm"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection