@extends('layouts.app_tamu') {{-- Pastikan layout utama Anda bernama app.blade.php atau sesuai sistem Anda --}}

@section('title', 'Rating & Saran Saya - SOWAN LPSE Karawang')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                💖 Ulasan & <span class="text-[#008f5d] dark:text-emerald-400">Feedback Anda</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Sampaikan pengalaman, kritik, atau saran untuk membantu kami meningkatkan layanan SOWAN
                </p>
            </div>
        </div>
    </div>

    {{-- Form Submit Rating --}}
    <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border-2 border-slate-200 dark:border-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <h2 class="text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-200 mb-6 flex items-center gap-2">
            <i class="fas fa-edit text-[#008f5d]"></i> Berikan Penilaian SOWAN
        </h2>
        
        <form action="{{ route('tamu.rating.simpan') }}" method="POST" class="space-y-6">
            @csrf
            {{-- Skor Rating Interaktif --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Bintang</label>
                <div class="flex gap-3" id="star-container">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer group">
                            <input type="radio" name="skor" value="{{ $i }}" class="hidden" required onchange="updateStars({{ $i }})">
                            <div class="star-box w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 text-slate-300 transition-all duration-300 ease-out shadow-inner">
                                <i class="fas fa-star text-xl pointer-events-none"></i>
                            </div>
                        </label>
                    @endfor
                </div>
            </div>

            {{-- Saran --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kritik & Saran</label>
                <textarea name="komentar" rows="3" required placeholder="Tuliskan ulasan atau saran untuk pelayanan SOWAN..."
                          class="w-full p-5 bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 placeholder:text-slate-400 transition-all"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-4 bg-[#008f5d] text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-[#007a4e] shadow-lg shadow-emerald-900/30 transition-all active:scale-95">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Penilaian SOWAN
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat Rating --}}
    <div class="space-y-6">
        <h2 class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Riwayat Penilaian Anda</h2>
        
        @forelse($rating as $item)
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border-2 border-slate-200 dark:border-slate-700 shadow-sm hover:border-[#008f5d]/50 transition-all duration-300 flex flex-col md:flex-row gap-6">
            <div class="shrink-0 text-center w-20 flex flex-col items-center justify-center">
                <div class="text-3xl font-black text-amber-500">{{ $item->skor }}</div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bintang</div>
            </div>
            
            <div class="flex-1 space-y-3">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300 italic p-4 bg-slate-50 dark:bg-slate-900 rounded-xl">"{{ $item->komentar }}"</p>
                
                {{-- Bagian Tanggapan Admin SOWAN --}}
                <div class="mt-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border-2 border-emerald-100 dark:border-emerald-800/50">
                    <p class="text-[9px] font-black uppercase text-[#008f5d] dark:text-emerald-400 mb-1">Respon Admin SOWAN:</p>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $item->tanggapan ?? 'Sedang menunggu ulasan ditinjau...' }}</p>
                </div>
            </div>
            
            <div class="text-[10px] font-bold text-slate-400 text-right min-w-[100px]">
                {{ $item->created_at->format('d M Y') }}
            </div>
        </div>
        @empty
        <div class="p-12 text-center border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-[2.5rem]">
            <i class="fas fa-comment-dots text-slate-300 text-3xl mb-4"></i>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada penilaian SOWAN yang dikirim</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    function updateStars(val) {
        const boxes = document.querySelectorAll('.star-box');
        boxes.forEach((box, index) => {
            if (index < val) {
                box.classList.add('bg-amber-100', 'text-amber-500', 'border-amber-400');
                box.classList.remove('bg-slate-50', 'text-slate-300', 'border-slate-200');
            } else {
                box.classList.remove('bg-amber-100', 'text-amber-500', 'border-amber-400');
                box.classList.add('bg-slate-50', 'text-slate-300', 'border-slate-200');
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#008f5d',
            customClass: { popup: 'rounded-[2rem]' }
        });
    @endif
</script>
@endsection