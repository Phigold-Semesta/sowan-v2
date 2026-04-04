@extends('layouts.app')

@section('title', 'Detail Tujuan - ' . $tujuan->nama_petugas)

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic transition-colors">
                Detail <span class="text-[#008f5d]">Tujuan</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 dark:text-emerald-500/60 uppercase tracking-[0.2em] mt-1 transition-colors">
                Informasi lengkap entitas tujuan kunjungan SOWAN 🎯
            </p>
        </div>
        <a href="{{ route('admin.master.tujuan.index') }}" 
            class="flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-900/30 shadow-2xl shadow-emerald-100/50 dark:shadow-none overflow-hidden transition-all duration-500">
        <div class="p-8 md:p-12">
            
            {{-- Profile/Icon Section --}}
            <div class="flex flex-col md:flex-row items-center gap-8 mb-12 pb-12 border-b border-slate-100 dark:border-slate-800">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-tr from-[#008f5d] to-emerald-400 rounded-[2.5rem] rotate-3 flex items-center justify-center shadow-xl shadow-emerald-200 dark:shadow-none">
                        <i class="fas fa-bullseye text-5xl text-white -rotate-3"></i>
                    </div>
                </div>
                <div class="text-center md:text-left space-y-2">
                    <div class="inline-block px-4 py-1 bg-emerald-100 dark:bg-emerald-500/10 text-[#008f5d] dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">
                        ID: #TJN-{{ str_pad($tujuan->id_petugas, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight transition-colors">{{ $tujuan->nama_petugas }}</h2>
                    <p class="text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-widest transition-colors">{{ $tujuan->jabatan }}</p>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                {{-- Nama Petugas / Unit --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Nama Petugas / Unit</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $tujuan->nama_petugas }}</span>
                    </div>
                </div>

                {{-- Jabatan / Bagian --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Jabatan / Bagian</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $tujuan->jabatan }}</span>
                    </div>
                </div>

                {{-- Dibuat Pada --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Data Dibuat</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $tujuan->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                </div>

                {{-- Terakhir Update --}}
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-800 dark:text-slate-400 uppercase tracking-[0.2em] ml-1 transition-colors">Pembaruan Terakhir</label>
                    <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all hover:bg-white dark:hover:bg-slate-800 hover:shadow-lg hover:shadow-slate-100 dark:hover:shadow-none group">
                        <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center text-[#008f5d] shadow-sm group-hover:bg-[#008f5d] group-hover:text-white transition-all">
                            <i class="fas fa-history"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $tujuan->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="mt-12 pt-8 border-t border-slate-50 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                {{-- Tombol Hapus --}}
                <form id="delete-form-{{ $tujuan->id_petugas }}" action="{{ route('admin.master.tujuan.destroy', $tujuan->id_petugas) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('{{ $tujuan->id_petugas }}', '{{ $tujuan->nama_petugas }}')" 
                        class="w-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus Data
                    </button>
                </form>

                {{-- Tombol Edit (Kanan) --}}
                <a href="{{ route('admin.master.tujuan.edit', $tujuan->id_petugas) }}" 
                    class="flex-[2] bg-[#008f5d] text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] text-center hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 dark:hover:shadow-emerald-900/40 transition-all active:scale-[0.98] shadow-md">
                    <i class="fas fa-edit mr-2"></i> Edit Tujuan Kunjungan
                </a>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'HAPUS DATA TUJUAN?',
            html: `Anda akan menghapus data <b>${name}</b>.<br><p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight italic">Tindakan ini permanen & tidak dapat dibatalkan!</p>`,
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
                
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }
</script>
@endsection