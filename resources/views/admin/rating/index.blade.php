@extends('layouts.app')

@section('title', 'Monitoring Rating')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Rating <span class="text-[#008f5d] dark:text-emerald-400">& Saran</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Pantau kepuasan tamu dan kualitas layanan SOWAN
                </p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.rating.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] dark:group-focus-within:text-emerald-400 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari komentar atau nama tamu..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                {{-- Skor Filter --}}
                <div class="w-full md:w-48 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <select name="skor" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">Semua Skor</option>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" {{ request('skor') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                {{-- Per Page Filter --}}
                <div class="w-full md:w-36 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-eye text-xs"></i>
                    </div>
                    <select name="per_page" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <button type="submit" class="px-6 py-3.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white dark:hover:bg-emerald-600 transition-all duration-300 shadow-sm active:scale-95">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 border-b border-emerald-50 dark:border-slate-700 text-nowrap">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Layanan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Penilaian</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($ratings as $rating)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-900/40 transition-all duration-200 group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <div class="p-0.5 rounded-[1.2rem] bg-gradient-to-tr from-emerald-100 to-white dark:from-emerald-900 dark:to-slate-800 shadow-sm group-hover:from-emerald-400 group-hover:to-emerald-200 transition-all duration-500">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($rating->kunjungan->tamu->nama_tamu) }}&background=008f5d&color=fff&bold=true" 
                                             class="w-12 h-12 rounded-[1.1rem] border-2 border-white dark:border-slate-700 object-cover group-hover:scale-105 transition-transform">
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate group-hover:text-[#008f5d] dark:group-hover:text-emerald-400 transition-colors">
                                        {{ $rating->kunjungan->tamu->nama_tamu }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                        {{ $rating->kunjungan->tamu->instansi ?? 'Umum' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[10px] font-black text-[#008f5d] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-800/30 text-nowrap">
                                {{ $rating->kunjungan->layanan->nama_layanan }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1 text-amber-400">
                                @for($i=1; $i<=5; $i++)
                                    <i class="{{ $i <= $rating->skor ? 'fas' : 'far' }} fa-star text-xs"></i>
                                @endfor
                                <span class="ml-2 text-xs font-black text-slate-700 dark:text-slate-300">({{ $rating->skor }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($rating->tanggapan)
                                <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                    <i class="fas fa-check-circle mr-1.5"></i> Sudah Ditanggapi
                                </span>
                            @else
                                <span class="inline-flex items-center text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl border bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800/30">
                                    <i class="fas fa-clock mr-1.5 animate-pulse"></i> Perlu Tanggapan
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('admin.rating.show', $rating->id_rating) }}" 
                                   title="Lihat Detail & Beri Tanggapan"
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 hover:shadow-[0_5px_15px_rgba(37,99,235,0.3)] transition-all active:scale-90">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form id="delete-form-{{ $rating->id_rating }}" action="{{ route('admin.rating.destroy', $rating->id_rating) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" 
                                        onclick="confirmDelete('{{ $rating->id_rating }}', '{{ $rating->kunjungan->tamu->nama_tamu }}')"
                                        class="w-10 h-10 flex items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white transition-all active:scale-90 shadow-sm"
                                        title="Hapus Rating">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100 dark:border-slate-800">
                                    <i class="fas fa-star-half-stroke text-4xl"></i>
                                </div>
                                <p class="text-sm font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Belum ada rating masuk</p>
                                <p class="text-xs text-slate-300 dark:text-slate-600 mt-2 italic font-bold">Data akan muncul setelah tamu mengisi penilaian</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($ratings instanceof \Illuminate\Pagination\LengthAwarePaginator && $ratings->hasPages())
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d] dark:bg-emerald-500"></div>
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
</div>

{{-- Script SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, tamuName) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (isDark ? 'text-white' : 'text-slate-800') + '">Hapus Rating?</span>',
            html: `
                <div class="mt-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed">
                        Anda akan menghapus data penilaian dari:<br>
                        <span class="text-rose-500 font-black">${tamuName}</span><br>
                        <span class="text-[10px] mt-2 block opacity-70">Tindakan ini permanen dan tidak bisa dibatalkan!</span>
                    </p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#f43f5e',
            background: isDark ? '#1e293b' : '#ffffff',
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS DATA',
            cancelButtonText: 'BATALKAN',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl px-4 py-8',
                confirmButton: 'ml-3 px-8 py-3 bg-[#008f5d] text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all active:scale-95',
                cancelButton: 'px-8 py-3 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-xs font-black uppercase tracking-widest rounded-full hover:bg-slate-300 dark:hover:bg-slate-600 transition-all active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            iconColor: '#008f5d',
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
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; transition: all 0.3s; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); }

    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #008f5d !important;
        border-color: #008f5d !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
        box-shadow: 0 4px 12px rgba(0,143,93,0.2);
    }
    .custom-pagination nav a, .custom-pagination nav span {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border: none !important;
        background-color: #ffffff;
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