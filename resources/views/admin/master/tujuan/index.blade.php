@extends('layouts.app')

@section('title', 'Tujuan Kunjungan')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        {{-- Judul (Sisi Kiri) --}}
        <div class="relative">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Tujuan <span class="text-[#008f5d] dark:text-emerald-400">Kunjungan</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] dark:bg-emerald-500 rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                    Kelola daftar petugas atau divisi tujuan tamu LPSE Karawang
                </p>
            </div>
        </div>
        
        {{-- Tombol Aksi (Sisi Kanan) --}}
        <div class="flex items-center gap-3 shrink-0">
            {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('admin.master.index') }}" 
               class="flex items-center gap-3 px-5 py-4 bg-white dark:bg-slate-800 text-slate-400 hover:text-[#008f5d] dark:hover:text-emerald-400 rounded-2xl border border-emerald-50 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-300 group active:scale-95"
               title="Kembali ke Menu Master">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] hidden sm:block">Kembali</span>
            </a>

            {{-- TOMBOL TAMBAH --}}
            <a href="{{ route('admin.master.tujuan.create') }}" 
               class="inline-flex items-center px-6 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group">
                <i class="fas fa-user-plus mr-2 group-hover:rotate-12 transition-transform duration-500 text-sm"></i>
                Tambah Petugas
            </a>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ route('admin.master.tujuan.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] dark:group-focus-within:text-emerald-400 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama petugas atau jabatan..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                <div class="w-full md:w-40 relative">
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
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">No</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Informasi Petugas / Tujuan</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Jabatan / Divisi</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50 dark:divide-slate-700/50">
                    @forelse($tujuan as $index => $item)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-900/40 transition-all duration-200 group">
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-slate-400 dark:text-slate-600 font-mono">
                                @if($tujuan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ str_pad($tujuan->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                @else
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight group-hover:text-[#008f5d] dark:group-hover:text-emerald-400 transition-colors italic">
                                    {{ $item->nama_petugas }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                                    ID: <span class="text-emerald-600 dark:text-emerald-500">TJN-{{ str_pad($item->id_petugas, 3, '0', STR_PAD_LEFT) }}</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400">
                                <i class="fas fa-briefcase mr-2 text-[#008f5d]/50 dark:text-emerald-500/50"></i>
                                {{ $item->jabatan }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Tombol Show (Detail) --}}
                                <a href="{{ route('admin.master.tujuan.show', $item->id_petugas) }}" 
                                   title="Detail Petugas"
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-[#008f5d] dark:text-emerald-400 hover:bg-[#008f5d] hover:text-white dark:hover:bg-emerald-500 hover:shadow-[0_5px_15px_rgba(0,143,93,0.3)] transition-all active:scale-90">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Edit (Updated Icon: Pen Nib) --}}
                                <a href="{{ route('admin.master.tujuan.edit', $item->id_petugas) }}" 
                                   title="Edit Petugas"
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 hover:shadow-[0_5px_15px_rgba(245,158,11,0.3)] transition-all active:scale-90">
                                    <i class="fas fa-pen-nib text-xs"></i>
                                </a>

                                {{-- Tombol Delete (Updated Icon: Trash Can) --}}
                                <form action="{{ route('admin.master.tujuan.destroy', $item->id_petugas) }}" 
                                      id="delete-form-{{ $item->id_petugas }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            title="Hapus Petugas"
                                            onclick="confirmDeleteTujuan('{{ $item->id_petugas }}', '{{ $item->nama_petugas }}')"
                                            class="w-10 h-10 flex items-center justify-center rounded-2xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 hover:shadow-[0_5px_15px_rgba(239,68,68,0.3)] transition-all active:scale-90">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mb-6 border border-slate-100 dark:border-slate-800">
                                    <i class="fas fa-users-slash text-4xl"></i>
                                </div>
                                <p class="text-sm font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Daftar tujuan tidak ditemukan</p>
                                <p class="text-xs text-slate-300 dark:text-slate-600 mt-2 italic font-bold">Mulai tambahkan petugas atau divisi tujuan kunjungan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Section --}}
        @if($tujuan->count() > 0)
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d] dark:bg-emerald-500"></div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">
                        @if($tujuan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            Tampil {{ $tujuan->firstItem() }} - {{ $tujuan->lastItem() }} dari {{ $tujuan->total() }} Petugas
                        @else
                            Menampilkan semua {{ $tujuan->count() }} Data Terdaftar
                        @endif
                    </p>
                </div>
                <div class="custom-pagination">
                    @if($tujuan instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $tujuan->appends(request()->query())->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Footer Info --}}
    <div class="mt-6 flex items-center gap-4 px-8 py-5 bg-white dark:bg-slate-800 border border-emerald-50 dark:border-slate-700 rounded-[2rem] shadow-sm transition-colors duration-300">
        <div class="shrink-0 w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center shadow-inner">
            <i class="fas fa-id-badge text-[#008f5d] dark:text-emerald-400 text-sm"></i>
        </div>
        <div class="flex flex-col">
            <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 uppercase tracking-[0.15em]">Informasi Master Data</p>
            <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-relaxed">
                Petugas yang terdaftar di sini akan muncul sebagai <span class="text-[#008f5d] dark:text-emerald-400 italic font-black">Opsi Tujuan</span> pada saat tamu melakukan pendaftaran kunjungan.
            </p>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; transition: all 0.3s; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); }

    /* Pagination Styling - SOWAN v2 Luxury */
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
    .custom-pagination nav a:hover {
        background-color: #ecfdf5;
        color: #008f5d;
        transform: translateY(-2px);
    }
    .dark .custom-pagination nav a:hover {
        background-color: #064e3b;
        color: #34d399;
    }

    .animate__fadeIn { animation-duration: 0.8s; }
</style>

<script>
    function confirmDeleteTujuan(id, name) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: 'HAPUS PETUGAS?',
            html: `Anda akan menghapus data petugas <b>${name}</b>.<br><p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight italic">Tamu tidak akan bisa memilih tujuan ini lagi!</p>`,
            icon: 'warning',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#334155' : '#f1f5f9',
            confirmButtonText: 'YA, HAPUS DATA',
            cancelButtonText: 'BATALKAN',
            reverseButtons: true,
            customClass: {
                popup: `rounded-[2.5rem] border-4 ${isDark ? 'border-slate-700' : 'border-red-50'}`,
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-6 py-3',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Menghapus...',
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                    didOpen: () => { Swal.showLoading() },
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }
</script>
@endsection