<div class="p-8">
    {{-- Header Modal --}}
    <div class="flex justify-between items-start mb-8">
        <div>
            <h3 class="text-xl font-black uppercase italic text-slate-800 dark:text-white">Detail Ulasan</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Evaluasi Tamu SOWAN V2</p>
        </div>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    {{-- Info Tamu --}}
    <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->kunjungan->tamu->nama_tamu ?? 'T') }}&background=046A38&color=fff" class="w-12 h-12 rounded-2xl shadow-sm">
        <div>
            <p class="text-sm font-black text-slate-800 dark:text-white uppercase">{{ $rating->kunjungan->tamu->nama_tamu ?? 'Anonim' }}</p>
            <p class="text-[10px] font-bold text-[#046A38] uppercase">{{ $rating->kunjungan->tamu->nama_instansi ?? 'Umum' }}</p>
        </div>
    </div>

    {{-- Konten Saran --}}
    <div class="mb-6">
        <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Saran & Ulasan</label>
        <div class="relative bg-slate-50 dark:bg-slate-900 p-5 rounded-2xl border-l-4 border-[#FFF200]">
            <p class="text-xs font-medium text-slate-600 dark:text-slate-300 italic">
                "{{ $rating->saran ?? 'Tidak ada ulasan yang diberikan.' }}"
            </p>
        </div>
    </div>

    {{-- Tanggapan Petugas --}}
    <div class="mb-8">
        <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block">Tanggapan Petugas</label>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-800">
            <p class="text-xs font-bold text-slate-700 dark:text-emerald-300">
                {{ $rating->tanggapan ?? 'Belum ada tanggapan dari petugas.' }}
            </p>
        </div>
    </div>

    {{-- Footer Action --}}
    <button type="button" onclick="closeModal()" 
            class="w-full py-4 bg-slate-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#046A38] transition-all shadow-lg shadow-slate-200 dark:shadow-none">
        Tutup Detail
    </button>
</div>