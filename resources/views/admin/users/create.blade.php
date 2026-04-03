@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
{{-- CSS Tambahan untuk menghilangkan ikon mata bawaan browser --}}
<style>
    /* Menghilangkan ikon mata bawaan Microsoft Edge */
    input::-ms-reveal,
    input::-ms-clear {
        display: none;
    }

    /* Menghilangkan ikon bawaan browser berbasis Webkit (Chrome/Safari) jika muncul */
    input::-webkit-contacts-auto-fill-button, 
    input::-webkit-credentials-auto-fill-button {
        visibility: hidden;
        display: none !important;
        pointer-events: none;
    }
</style>

<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">
                Tambah <span class="text-[#008f5d]">Pengguna</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">
                Daftarkan administrator atau petugas baru ke sistem
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" 
            class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-300 rounded-2xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[2.5rem] border border-emerald-50 shadow-2xl shadow-emerald-100/50 overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 md:p-12 space-y-8" autocomplete="off">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Lengkap --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                            placeholder="Masukkan nama lengkap...">
                    </div>
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-at text-sm"></i>
                        </div>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                            placeholder="username_baru">
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-12 pr-14 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                            placeholder="••••••••">
                        {{-- Tombol Mata Kustom --}}
                        <button type="button" onclick="togglePassword('password', 'toggle-icon-1')" 
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-[#008f5d] transition-colors focus:outline-none z-10">
                            <i id="toggle-icon-1" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full pl-12 pr-14 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                            placeholder="••••••••">
                        {{-- Tombol Mata Kustom --}}
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggle-icon-2')" 
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-[#008f5d] transition-colors focus:outline-none z-10">
                            <i id="toggle-icon-2" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Role & Jabatan tetap sama seperti sebelumnya --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Hak Akses (Role)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user-tag text-sm"></i>
                        </div>
                        <select name="role" required class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all appearance-none">
                            <option value="" disabled selected>Pilih Role...</option>
                            <option value="administrator">Administrator</option>
                            <option value="petugas">Petugas</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Jabatan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                            placeholder="Contoh: Staff IT">
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <a href="{{ route('admin.users.index') }}" class="order-2 md:order-1 flex-1 bg-slate-200 text-slate-700 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] text-center hover:bg-slate-300 transition-all">Batal</a>
                <button type="submit" class="order-1 md:order-2 flex-[2] bg-[#008f5d] text-white py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 transition-all active:scale-[0.98]">
                    <i class="fas fa-save mr-2"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection