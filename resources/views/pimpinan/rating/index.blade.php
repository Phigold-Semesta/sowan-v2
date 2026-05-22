@extends('layouts.app')

@section('title', 'Monitoring Rating & Saran - Pimpinan SOWAN V2')

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
                    Ringkasan evaluasi pelayanan tamu LPSE Karawang untuk pimpinan
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <span class="inline-block bg-white dark:bg-slate-800 text-[#046A38] dark:text-emerald-400 font-black text-xs uppercase tracking-widest px-4 py-2.5 rounded-2xl shadow-sm border border-emerald-50 dark:border-slate-700">
                PIMPINAN DASHBOARD
            </span>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm">
        <form action="{{ route('pimpinan.rating.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#046A38]/20 dark:text-slate-200">
            </div>
            <button type="submit" class="px-8 py-3.5 bg-[#046A38] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#03532B] transition-all active:scale-95 flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> Filter Data
            </button>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700 text-nowrap">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Penilaian</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700">
                    @forelse($ratings as $rating)
                    <tr class="hover:bg-emerald-50/40 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->kunjungan->tamu->nama_tamu ?? 'T') }}&background=046A38&color=fff" class="w-10 h-10 rounded-2xl">
                                <div>
                                    <p class="text-sm font-black text-slate-800 dark:text-white uppercase">{{ $rating->kunjungan->tamu->nama_tamu ?? 'Anonim' }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $rating->kunjungan->tamu->nama_instansi ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[10px] font-black text-[#046A38] bg-emerald-50 px-3 py-1 rounded-xl uppercase">
                                {{ $rating->kunjungan->layanan->nama_layanan ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rating->skor ? 'fas' : 'far' }} fa-star text-xs"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($rating->tanggapan)
                                <span class="text-[9px] font-black uppercase text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Ditanggapi</span>
                            @else
                                <span class="text-[9px] font-black uppercase text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Pending</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            {{-- Tombol Pemicu AJAX --}}
                            <button type="button" 
                                    onclick="loadDetailRating('{{ route('pimpinan.rating.show', $rating->id_rating) }}')"
                                    class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center hover:bg-emerald-100 transition-all">
                                <i class="fas fa-eye text-[#046A38]"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-10 text-center text-slate-400 text-xs font-bold uppercase">Belum ada data rating</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-900/30">
            {{ $ratings->links('pagination::tailwind') }}
        </div>
    </div>
</div>

{{-- Modal Tunggal untuk AJAX --}}
<div id="modalDetailRating" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalContent" class="bg-white dark:bg-slate-800 rounded-[2.5rem] w-full max-w-lg shadow-2xl animate__animated animate__zoomIn animate__faster">
            {{-- Konten disuntik via AJAX --}}
        </div>
    </div>
</div>

<script>
    function loadDetailRating(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('modalDetailRating').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => console.error('Error:', error));
    }

    function closeModal() {
        document.getElementById('modalDetailRating').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #046A3833; border-radius: 10px; }
</style>
@endsection