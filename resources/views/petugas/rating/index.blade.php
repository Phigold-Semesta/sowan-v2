@extends('layouts.app')

@section('title', 'Rating & Saran Layanan - SOWAN V2')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                ⭐ Monitoring Rating <span class="text-[#046A38] dark:text-emerald-400">& Saran Layanan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#046A38] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Pantau tingkat kepuasan, ulasan, serta saran dari tamu LPSE Karawang secara real-time
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <span class="inline-block bg-white dark:bg-slate-800 text-[#046A38] dark:text-emerald-400 font-black text-xs uppercase tracking-widest px-4 py-2.5 rounded-2xl shadow-sm border border-emerald-50 dark:border-slate-700">
                SOWAN V2 Premium UI
            </span>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('petugas.rating.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#046A38] dark:group-focus-within:text-emerald-400 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama tamu atau saran..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#046A38]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                {{-- Skor Filter --}}
                <div class="w-full md:w-56 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <select name="skor_rating" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#046A38]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">--- Semua Rating Bintang ---</option>
                        <option value="5" {{ request('skor_rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4" {{ request('skor_rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3" {{ request('skor_rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                        <option value="2" {{ request('skor_rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 Bintang)</option>
                        <option value="1" {{ request('skor_rating') == '1' ? 'selected' : '' }}>⭐ (1 Bintang)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="px-6 py-3.5 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#03532B] transition-all duration-300 shadow-sm active:scale-95 flex items-center gap-2">
                        <i class="fas fa-filter text-xs"></i> Filter
                    </button>
                    
                    @if(request()->filled('search') || request()->filled('skor_rating'))
                        <a href="{{ route('petugas.rating.index') }}" class="px-4 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Content Section: Separated Luxury Cards --}}
    <div class="space-y-4">
        @forelse($ratings as $rating)
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border-l-4 border-[#046A38] dark:border-emerald-500 border-t border-r border-b border-emerald-50/50 dark:border-slate-700 p-6 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 group">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    
                    {{-- Profil Tamu --}}
                    <div class="md:col-span-4 flex items-center gap-4">
                        <div class="relative shrink-0">
                            <div class="p-0.5 rounded-[1.2rem] bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 shadow-sm group-hover:from-[#046A38] group-hover:to-emerald-400 transition-all duration-500">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->kunjungan->tamu->nama_tamu ?? 'T') }}&background=046A38&color=fff&bold=true" 
                                     class="w-14 h-14 rounded-[1.1rem] border-2 border-white dark:border-slate-700 object-cover group-hover:scale-105 transition-transform">
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-base font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate group-hover:text-[#046A38] dark:group-hover:text-emerald-400 transition-colors">
                                {{ $rating->kunjungan->tamu->nama_tamu ?? 'Anonim' }}
                            </p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider truncate mt-0.5">
                                📩 {{ $rating->kunjungan->gmail ?? '-' }}
                            </p>
                            <span class="inline-block mt-1.5 text-[9px] font-bold bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 px-2.5 py-0.5 rounded border border-slate-200/50 dark:border-slate-800 truncate max-w-full">
                                🏛️ {{ $rating->kunjungan->tamu->nama_instansi ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Layanan & Penilaian Bintang --}}
                    <div class="md:col-span-5 space-y-2.5">
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 text-[9px] font-black uppercase tracking-[0.2em] block mb-1">Layanan Yang Diakses</span>
                            <span class="text-[10px] font-black text-[#046A38] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                💼 {{ $rating->kunjungan->layanan->nama_layanan ?? 'Layanan Umum' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 text-amber-400">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rating->skor ? 'fas' : 'far' }} fa-star text-xs"></i>
                                @endfor
                            </div>
                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">({{ $rating->skor }}/5)</span>
                        </div>
                    </div>

                    {{-- Waktu & Tombol Aksi --}}
                    <div class="md:col-span-3 flex flex-col md:items-end justify-between h-full gap-4 md:gap-3">
                        <div class="text-left md:text-right space-y-1">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($rating->waktu_rating)->translatedFormat('d M Y, H:i') }} WIB
                            </p>
                            <div>
                                @if($rating->tanggapan)
                                    <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                        <i class="fas fa-check-circle mr-1"></i> Ditanggapi
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-xl border bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30">
                                        <i class="fas fa-clock mr-1 animate-pulse"></i> Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <button type="button" 
                                onclick="openModalRating('{{ $rating->id_rating ?? $loop->index }}')"
                                class="w-full md:w-auto px-5 py-3 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#03532B] hover:shadow-[0_5px_15px_rgba(4,106,56,0.3)] transition-all duration-300 active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-comment-dots text-xs"></i> Lihat Ulasan
                        </button>
                    </div>

                </div>
            </div>

            {{-- MODAL INTERAKTIF (DI-RENDER DI DALAM LOOP AGAR SESUAI DATA RATING) --}}
            <div id="modalDetailRating-{{ $rating->id_rating ?? $loop->index }}" class="fixed inset-0 z-55 hidden overflow-y-auto" aria-hidden="true">
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModalRating('{{ $rating->id_rating ?? $loop->index }}')"></div>
                
                {{-- Modal Content --}}
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <div class="relative w-full max-w-lg transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-800 text-left align-middle shadow-2xl transition-all border border-emerald-50 dark:border-slate-700 animate__animated animate__zoomIn animate__faster">
                        {{-- Header --}}
                        <div class="px-8 py-6 bg-gradient-to-r from-[#046A38] to-[#023e20] text-white flex items-center justify-between">
                            <h3 class="text-md font-black uppercase tracking-tight italic flex items-center gap-2">
                                <i class="fas fa-star text-amber-400"></i> Detail Kritik & Saran
                            </h3>
                            <button type="button" onclick="closeModalRating('{{ $rating->id_rating ?? $loop->index }}')" class="text-white/70 hover:text-white transition-colors text-xl">
                                &times;
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="p-8 space-y-6">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border-l-4 border-[#FFF200]">
                                <label class="block text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Kritik, Ulasan, atau Saran Tamu:</label>
                                <p class="text-slate-700 dark:text-slate-300 text-xs font-bold italic leading-relaxed">
                                    "{{ $rating->saran ?? 'Tamu tidak memberikan ulasan teks tertulis (Hanya memberikan rating bintang).' }}"
                                </p>
                            </div>

                            {{-- Form Tanggapan Petugas --}}
                            <form action="{{ route('petugas.rating.tanggapan', $rating->id_rating ?? 1) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-2">
                                    <label class="block text-slate-700 dark:text-slate-300 text-xs font-black uppercase tracking-tight">Kirim Tanggapan / Tindak Lanjut Petugas:</label>
                                    <textarea name="tanggapan" rows="3" required placeholder="Tulis tanggapan atau konfirmasi perbaikan dari LPSE..."
                                              class="w-full p-4 bg-slate-50 dark:bg-slate-900 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#046A38]/20 dark:text-slate-200 placeholder:text-slate-400 dark:placeholder:text-slate-600 leading-relaxed">{{ $rating->tanggapan ?? '' }}</textarea>
                                    <p class="text-[9px] text-slate-400 dark:text-slate-500 font-medium">*Tanggapan ini bersifat opsional untuk memberikan feedback positif kepada pelapor/tamu jika diperlukan.</p>
                                </div>

                                {{-- Actions Footer --}}
                                <div class="flex justify-end gap-2 pt-2 border-t border-emerald-50/30 dark:border-slate-700">
                                    <button type="button" onclick="closeModalRating('{{ $rating->id_rating ?? $loop->index }}')"
                                            class="px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                                        Tutup
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#03532B] shadow-lg shadow-emerald-900/20 transition-all flex items-center gap-2">
                                        <i class="fas fa-paper-plane"></i> Simpan Tanggapan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl py-24 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100 dark:border-slate-800">
                        <i class="fas fa-star-half-stroke text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <p class="text-sm font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Belum ada rating masuk</p>
                    <p class="text-xs text-slate-300 dark:text-slate-600 mt-2 italic font-bold">Data akan muncul setelah tamu mengisi penilaian</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($ratings instanceof \Illuminate\Pagination\LengthAwarePaginator && $ratings->hasPages())
        <div class="p-4 bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm mt-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 px-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#046A38] dark:bg-emerald-500"></div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">
                        Tampil {{ $ratings->firstItem() ?? 0 }} - {{ $ratings->lastItem() ?? 0 }} dari {{ $ratings->total() }} Penilaian
                    </p>
                </div>
                <div class="custom-pagination">
                    {{ $ratings->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Script SweetAlert2 & Modal Handler --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi Manajemen Modal Tailwind Manual (Bebas dari dependensi Bootstrap)
    function openModalRating(id) {
        const modal = document.getElementById('modalDetailRating-' + id);
        if(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Lock scroll body belakang
        }
    }

    function closeModalRating(id) {
        const modal = document.getElementById('modalDetailRating-' + id);
        if(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scroll body
        }
    }

    // Flash Alert Notifikasi menggunakan SweetAlert2 dengan style Premium SOWAN V2
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            iconColor: '#046A38',
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (document.documentElement.classList.contains('dark') ? 'text-white' : 'text-slate-800') + '">Berhasil!</span>',
            text: "{{ session('success') }}",
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-10',
                htmlContainer: 'text-xs font-bold text-slate-400 uppercase tracking-widest'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            iconColor: '#f43f5e',
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (document.documentElement.classList.contains('dark') ? 'text-white' : 'text-slate-800') + '">Gagal!</span>',
            text: "{{ session('error') }}",
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            showConfirmButton: true,
            confirmButtonText: 'TUTUP',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-10',
                confirmButton: 'px-8 py-3 bg-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-full'
            }
        });
    @endif
</script>

<style>
    /* Custom Tailwind Pagination Adjustments */
    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #046A38 !important;
        border-color: #046A38 !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
        box-shadow: 0 4px 12px rgba(4,106,56,0.2);
    }
    .custom-pagination nav a, .custom-pagination nav span {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        background-color: #f8fafc;
        color: #64748b;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }

    .dark .custom-pagination nav a, .dark .custom-pagination nav span:not([aria-current="page"] > span) {
        background-color: #0f172a;
        color: #94a3b8;
    }
</style>
@endsection