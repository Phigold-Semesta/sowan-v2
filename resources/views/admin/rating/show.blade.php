@extends('layouts.app')

@section('title', 'Detail Rating & Tanggapan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeIn">
    {{-- Header & Back Button --}}
    <div class="flex items-center gap-6">
        <a href="{{ route('admin.rating.index') }}" 
           class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-slate-400 hover:text-[#008f5d] dark:hover:text-emerald-400 shadow-sm border border-emerald-50 dark:border-slate-700 transition-all hover:scale-110 active:scale-90">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Detail <span class="text-[#008f5d] dark:text-emerald-400">Rating</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                Berikan tanggapan resmi untuk masukan tamu
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden transition-colors duration-300">
        {{-- Card Header: User Profile Summary --}}
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-b border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="p-1 rounded-[1.5rem] bg-gradient-to-tr from-emerald-400 to-emerald-200 shadow-lg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->kunjungan->tamu->nama_tamu) }}&background=008f5d&color=fff&bold=true" 
                             class="w-16 h-16 rounded-[1.4rem] border-4 border-white dark:border-slate-800 object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $rating->kunjungan->tamu->nama_tamu }}
                        </h2>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 rounded-lg">
                                <i class="fas fa-building mr-1"></i> {{ $rating->kunjungan->tamu->nama_instansi }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg">
                                <i class="fas fa-envelope mr-1"></i> {{ $rating->kunjungan->tamu->gmail }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:items-end justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Penilaian Layanan</p>
                    <div class="flex items-center gap-1 text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-4 py-2 rounded-2xl border border-amber-100 dark:border-amber-800/30">
                        @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $rating->skor ? 'fas' : 'far' }} fa-star text-sm"></i>
                        @endfor
                        <span class="ml-2 text-sm font-black text-amber-600 dark:text-amber-400">{{ $rating->skor }}.0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-10 space-y-10">
            {{-- Layanan Terkait --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Jenis Layanan</label>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            <i class="fas fa-concierge-bell text-[#008f5d] mr-2"></i>
                            {{ $rating->kunjungan->layanan->nama_layanan }}
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Waktu Kunjungan</label>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            <i class="fas fa-calendar-alt text-[#008f5d] mr-2"></i>
                            {{ $rating->created_at->translatedFormat('d F Y - H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            {{-- Komentar Tamu --}}
            <div class="relative">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1 mb-2 block">Isi Komentar / Saran</label>
                <div class="relative p-6 bg-emerald-50/30 dark:bg-emerald-900/10 rounded-[2rem] border-2 border-dashed border-emerald-100 dark:border-emerald-800/30">
                    <i class="fas fa-quote-left absolute top-4 left-4 text-emerald-200 dark:text-emerald-800 text-3xl"></i>
                    <p class="text-lg font-medium text-slate-700 dark:text-slate-200 italic leading-relaxed relative z-10 px-6">
                        "{{ $rating->komentar ?? 'Tamu tidak meninggalkan komentar tertulis.' }}"
                    </p>
                </div>
            </div>

            <hr class="border-emerald-50 dark:border-slate-700">

            {{-- Form Tanggapan --}}
            <form action="{{ route('admin.rating.update', $rating->id_rating) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label for="tanggapan" class="text-[10px] font-black text-[#008f5d] dark:text-emerald-400 uppercase tracking-[0.2em] ml-1">
                            Tanggapan Admin <span class="text-slate-400 font-bold">(Dikirim via Email)</span>
                        </label>
                    </div>
                    <textarea name="tanggapan" id="tanggapan" rows="5" required
                              class="w-full p-6 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-[2rem] text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-[#008f5d]/10 dark:focus:ring-emerald-500/10 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600"
                              placeholder="Tuliskan apresiasi atau jawaban konfirmasi Anda di sini...">{{ $rating->tanggapan }}</textarea>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4 pt-4">
                    <button type="submit" 
                            class="w-full md:flex-1 inline-flex items-center justify-center px-8 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group">
                        <i class="fas fa-paper-plane mr-2 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        Kirim & Simpan Tanggapan
                    </button>
                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-tighter text-center md:text-left md:max-w-[200px]">
                        *Tanggapan akan otomatis terkirim ke alamat email tamu yang bersangkutan.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Menyelaraskan scrollbar dan elemen form dengan tema SOWAN v2 */
    textarea {
        resize: none;
    }
    textarea::-webkit-scrollbar { width: 5px; }
    textarea::-webkit-scrollbar-track { background: transparent; }
    textarea::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
</style>
@endsection