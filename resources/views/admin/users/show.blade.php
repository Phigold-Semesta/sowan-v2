@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic transition-colors">
                Detail <span class="text-[#008f5d]">Pengguna</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.2em] mt-1 transition-colors">
                Informasi lengkap akun pengguna sistem SOWAN 🔐
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" 
            class="flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-900/30 shadow-2xl shadow-emerald-100/50 dark:shadow-none overflow-hidden transition-all duration-500">
        <div class="p-8 md:p-12">
            
            {{-- Profile Section --}}
            <div class="flex flex-col md:flex-row items-center gap-8 mb-12 pb-12 border-b border-slate-100 dark:border-slate-800">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-tr from-[#008f5d] to-emerald-400 rounded-[2.5rem] rotate-3 flex items-center justify-center shadow-xl shadow-emerald-200 dark:shadow-none">
                        <i class="fas fa-user-tie text-5xl text-white -rotate-3"></i>
                    </div>
                </div>
                <div class="text-center md:text-left space-y-2">
                    <div class="inline-block px-4 py-1 bg-emerald-100 dark:bg-emerald-500/10 text-[#008f5d] dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">
                        {{ strtoupper($user->role) }}
                    </div>
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight transition-colors">{{ $user->nama_lengkap }}</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-widest transition-colors">{{ $user->jabatan }}</p>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                {{-- Username --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Username Login</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-at"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $user->username }}</span>
                    </div>
                </div>

                {{-- Dibuat Pada --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Terdaftar Sejak</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                {{-- Status Akun --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Status Keamanan</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        @if($user->username)
                            <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-emerald-500 shadow-sm group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 italic flex items-center gap-2 transition-colors">
                                <i class="fas fa-shield-check text-[10px]"></i> Terverifikasi Sistem 
                            </span>
                        @else
                            <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-amber-500 shadow-sm group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="text-sm font-black text-amber-600 dark:text-amber-400 italic flex items-center gap-2 transition-colors">
                                <i class="fas fa-user-slash text-[10px]"></i> Belum Terverifikasi 
                            </span>
                        @endif
                    </div>
                </div>

                {{-- User ID --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">ID Pengguna</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-id-badge"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">#{{ str_pad($user->id_user, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="mt-12 pt-8 border-t border-slate-50 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                {{-- Tombol Hapus / Label Akun (Kiri) --}}
                @if(Auth::id() !== $user->id_user)
                <form id="delete-form-{{ $user->id_user }}" action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ $user->id_user }}', '{{ $user->nama_lengkap }}')" 
                        class="w-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Akun
                    </button>
                </form>
                @else
                <div class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center flex items-center justify-center gap-2 cursor-not-allowed">
                    <i class="fas fa-lock text-xs"></i> Akun Sedang Digunakan
                </div>
                @endif

                {{-- Tombol Edit (Kanan) --}}
                <a href="{{ route('admin.users.edit', $user->id_user) }}" 
                    class="flex-[2] bg-[#008f5d] text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] text-center hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 dark:hover:shadow-emerald-900/40 transition-all active:scale-[0.98] shadow-md">
                    <i class="fas fa-user-edit mr-2"></i> Edit Data Akun
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    /**
     * Konfirmasi Penghapusan User dengan Gaya SOWAN v2 (Dark/Light Mode Support)
     */
    function confirmDelete(id, name) {
        // Cek mode saat ini
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'HAPUS PENGGUNA?',
            html: `Anda akan menghapus user <b>${name}</b>.<br><p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight italic">Tindakan ini permanen & tidak dapat dibatalkan!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#334155' : '#f1f5f9',
            confirmButtonText: 'YA, HAPUS SEKARANG',
            cancelButtonText: 'BATALKAN',
            reverseButtons: true,
            background: isDark ? '#0f172a' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            customClass: {
                popup: `rounded-[2.5rem] border-4 ${isDark ? 'border-slate-800 shadow-none' : 'border-red-50 shadow-2xl'}`,
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-6 py-3 uppercase',
                cancelButton: `rounded-2xl font-black text-[10px] tracking-widest px-6 py-3 uppercase ${isDark ? 'text-slate-300' : 'text-slate-500'}`
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan Loading State
                Swal.fire({
                    title: 'Memproses...',
                    html: '<p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sedang menghapus data dari sistem</p>',
                    background: isDark ? '#0f172a' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-[2rem]'
                    }
                });
                
                // Submit Form
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }
</script>
@endsection