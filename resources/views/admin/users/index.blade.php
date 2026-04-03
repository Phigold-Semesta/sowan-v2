@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">
                Daftar <span class="text-[#008f5d]">Pengguna</span>
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1 w-8 bg-[#008f5d] rounded-full"></span>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                    Kelola hak akses administrator, petugas, dan pimpinan
                </p>
            </div>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-[#008f5d] text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-700 hover:shadow-[0_10px_25px_rgba(0,143,93,0.3)] transition-all duration-300 group shrink-0">
            <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-500"></i>
            Tambah User Baru
        </a>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white p-5 rounded-[2.5rem] border border-emerald-50 shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Input --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau username..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-0 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-[#008f5d]/20 transition-all placeholder:text-slate-400">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                {{-- Role Filter --}}
                <div class="w-full md:w-48 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-user-tag text-xs"></i>
                    </div>
                    <select name="role" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="administrator" {{ request('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                        <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
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
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 appearance-none cursor-pointer">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>

                <button type="submit" class="px-6 py-3.5 bg-emerald-100 text-[#008f5d] text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-[2.5rem] border border-emerald-50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-emerald-50 text-nowrap">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Pengguna</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identitas Akun</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Hak Akses</th>
                        <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jabatan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-emerald-50/40 transition-all duration-200 group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <div class="p-0.5 rounded-[1.2rem] bg-gradient-to-tr from-emerald-100 to-white shadow-sm group-hover:from-emerald-400 group-hover:to-emerald-200 transition-all duration-500">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_lengkap) }}&background=008f5d&color=fff&bold=true" 
                                             class="w-12 h-12 rounded-[1.1rem] border-2 border-white object-cover group-hover:scale-105 transition-transform">
                                    </div>
                                    @if(Auth::id() == $user->id_user)
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 uppercase tracking-tight truncate group-hover:text-[#008f5d] transition-colors">
                                        {{ $user->nama_lengkap }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                        ID: <span class="text-slate-500">#{{ str_pad($user->id_user, 3, '0', STR_PAD_LEFT) }}</span>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-[#008f5d] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100 w-fit">
                                    @ {{ $user->username }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $roleClasses = [
                                    'administrator' => 'bg-red-50 text-red-600 border-red-100',
                                    'petugas' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pimpinan' => 'bg-amber-50 text-amber-600 border-amber-100',
                                ];
                                $class = $roleClasses[$user->role] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-xl border {{ $class }}">
                                <i class="fas fa-shield-halved mr-1.5 opacity-70"></i>
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-xs font-bold text-slate-600 italic">
                            {{ $user->jabatan }}
                        </td>
                        <td class="px-8 py-5 text-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                {{-- Tombol Show/Detail --}}
                                <a href="{{ route('admin.users.show', $user->id_user) }}" 
                                   title="Lihat Detail"
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white hover:shadow-[0_5px_15px_rgba(37,99,235,0.3)] transition-all active:scale-90">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id_user) }}" 
                                   title="Edit Data"
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white hover:shadow-[0_5px_15px_rgba(245,158,11,0.3)] transition-all active:scale-90">
                                    <i class="fas fa-pen-nib text-xs"></i>
                                </a>

                                {{-- Tombol Delete --}}
                                @if(Auth::id() != $user->id_user)
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" 
                                      id="delete-form-{{ $user->id_user }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            title="Hapus User"
                                            onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->nama_lengkap }}')"
                                            class="w-10 h-10 flex items-center justify-center rounded-2xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white hover:shadow-[0_5px_15px_rgba(239,68,68,0.3)] transition-all active:scale-90">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                                @else
                                <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200" 
                                      title="Akun Sedang Digunakan">
                                    <i class="fas fa-user-lock text-xs"></i>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center text-slate-200 mb-6 border border-slate-100">
                                    <i class="fas fa-users-slash text-4xl"></i>
                                </div>
                                <p class="text-sm font-black text-slate-400 uppercase tracking-widest">Data pengguna tidak ditemukan</p>
                                <p class="text-xs text-slate-300 mt-2 italic font-bold">Coba ubah kata kunci atau filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages() || request('per_page') == 'all')
        <div class="px-10 py-8 bg-slate-50/50 border-t border-emerald-50">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d]"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                        @if(request('per_page') == 'all')
                            Menampilkan semua {{ $users->count() }} User Aktif
                        @else
                            Tampil {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} User
                        @endif
                    </p>
                </div>
                <div class="custom-pagination">
                    {{ $users->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Global Styling for SOWAN v2 */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Pagination Styling */
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
    .custom-pagination nav a:hover {
        background-color: #ecfdf5;
        color: #008f5d;
        transform: translateY(-2px);
    }
</style>

<script>
    /**
     * Konfirmasi Penghapusan User dengan SweetAlert2
     */
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'HAPUS PENGGUNA?',
            html: `Anda akan menghapus user <b>${name}</b>.<br><p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight italic">Tindakan ini permanen & tidak dapat dibatalkan!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'YA, HAPUS SEKARANG',
            cancelButtonText: 'BATALKAN',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2.5rem] border-4 border-red-50',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-6 py-3',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    didOpen: () => { Swal.showLoading() },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }
</script>
@endsection