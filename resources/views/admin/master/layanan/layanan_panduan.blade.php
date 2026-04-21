@extends('layouts.app')

@section('title', 'Manajemen Panduan Layanan')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">
                Manajemen <span class="text-[#008f5d] dark:text-emerald-400">Panduan</span>
            </h1>
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                Layanan: <span class="text-slate-700 dark:text-slate-300">{{ $layanan->nama_layanan }}</span>
            </p>
        </div>
        
        <a href="{{ route('admin.master.layanan.index') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-widest rounded-full shadow-sm border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all active:scale-95">
            <i class="fas fa-arrow-left mr-2.5 text-[#008f5d]"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Kolom Kiri: Form Upload Multiple --}}
        <div class="lg:col-span-5">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 md:p-10 shadow-xl border border-emerald-50 dark:border-slate-700 sticky top-8">
                <div class="mb-8">
                    <h5 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight italic">
                        Unggah <span class="text-[#008f5d]">File Baru</span>
                    </h5>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Anda dapat memilih lebih dari satu file PDF sekaligus</p>
                </div>

                <form action="{{ route('admin.master.layanan.panduan.store', $layanan->id_layanan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="relative group">
                        <label for="file_panduan" class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3 ml-1">
                            Pilih Berkas (.PDF)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                                <i class="fas fa-copy text-sm"></i>
                            </div>
                            <input type="file" 
                                   name="file_panduan[]" 
                                   id="file_panduan" 
                                   accept="application/pdf"
                                   multiple
                                   class="w-full pl-14 pr-6 py-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d]/20 focus:border-[#008f5d] transition-all file:hidden @error('file_panduan.*') border-red-300 @enderror"
                                   required>
                        </div>
                        @error('file_panduan.*')
                            <p class="text-xs text-red-500 font-bold mt-2 ml-1 animate__animated animate__headShake">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full px-10 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-emerald-700 dark:hover:bg-emerald-500 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all active:scale-95 flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt mr-2.5 text-lg"></i>
                        Mulai Unggah
                    </button>
                </form>

                <div class="mt-10 p-6 bg-slate-50 dark:bg-slate-900/40 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                    <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Ketentuan:</h6>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-[#008f5d] text-xs mt-0.5"></i>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-relaxed">Maksimal 5MB per file.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-[#008f5d] text-xs mt-0.5"></i>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-relaxed">Hanya menerima format PDF.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Dokumen yang Sudah Diunggah --}}
        <div class="lg:col-span-7">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 md:p-10 shadow-xl border border-emerald-50 dark:border-slate-700 min-h-[500px]">
                <div class="flex items-center justify-between mb-8">
                    <h5 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight italic">
                        Daftar <span class="text-[#008f5d]">Panduan Aktif</span>
                    </h5>
                    <span class="px-4 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] text-[10px] font-black rounded-full uppercase tracking-widest">
                        {{ count($dokumen_list) }} File
                    </span>
                </div>

                @if(count($dokumen_list) > 0)
                    <div class="space-y-4">
                        @foreach($dokumen_list as $doc)
                            <div class="group flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-slate-700 hover:border-[#008f5d]/30 transition-all">
                                <div class="flex items-center gap-4 overflow-hidden">
                                    <div class="flex-shrink-0 w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center shadow-sm text-[#008f5d]">
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200 truncate group-hover:text-[#008f5d] transition-colors">
                                            {{ $doc->nama_dokumen }}
                                        </p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">
                                            Oleh: {{ $doc->user->nama_lengkap ?? 'Admin' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2 pl-4">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" 
                                       target="_blank"
                                       class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-400 hover:text-[#008f5d] rounded-full shadow-sm border border-slate-100 dark:border-slate-700 transition-all active:scale-90"
                                       title="Lihat Dokumen">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <form id="delete-form-{{ $doc->id_dokumen }}" action="{{ route('admin.master.layanan.panduan.destroy', $doc->id_dokumen) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $doc->id_dokumen }}', '{{ $doc->nama_dokumen }}')"
                                            class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-400 hover:text-red-500 rounded-full shadow-sm border border-slate-100 dark:border-slate-700 transition-all active:scale-90"
                                            title="Hapus Dokumen">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center opacity-50">
                        <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-folder-open text-3xl text-slate-300"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Belum ada panduan yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Script SweetAlert2 with Dual Mode & Customized Layout --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, fileName) {
        // Cek apakah sedang mode dark
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (isDark ? 'text-white' : 'text-slate-800') + '">Hapus Dokumen?</span>',
            html: `
                <div class="mt-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed">
                        Anda akan menghapus file:<br>
                        <span class="text-red-500 font-black">${fileName}</span><br>
                        <span class="text-[10px] mt-2 block opacity-70">Tindakan ini bersifat permanen!</span>
                    </p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#f43f5e',
            background: isDark ? '#1e293b' : '#ffffff', // Slate-800 atau Putih
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS DATA',
            cancelButtonText: 'BATALKAN',
            reverseButtons: true, // PENTING: Menukar posisi tombol (Cancel kiri, Confirm kanan)
            
            // Styling Tombol
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

    // Notifikasi Berhasil (Auto Dual Mode)
    @if(session('success'))
        const isDarkSuccess = document.documentElement.classList.contains('dark');
        Swal.fire({
            icon: 'success',
            iconColor: '#008f5d',
            title: '<span class="text-2xl font-black italic uppercase tracking-tighter ' + (isDarkSuccess ? 'text-white' : 'text-slate-800') + '">Berhasil!</span>',
            text: "{{ session('success') }}",
            background: isDarkSuccess ? '#1e293b' : '#ffffff',
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                popup: 'rounded-[2.5rem] border-none shadow-2xl p-10',
                title: 'text-emerald-500',
                htmlContainer: 'text-xs font-bold text-slate-400 uppercase tracking-widest'
            }
        });
    @endif
</script>
@endsection