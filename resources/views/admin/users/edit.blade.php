@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->nama_lengkap)

@section('content')
{{-- CSS untuk menghilangkan ikon mata bawaan browser --}}
<style>
    input::-ms-reveal, input::-ms-clear { display: none; }
    input::-webkit-contacts-auto-fill-button, 
    input::-webkit-credentials-auto-fill-button {
        visibility: hidden; display: none !important; pointer-events: none;
    }
</style>

<div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeInUp">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">
                Edit <span class="text-[#008f5d]">Pengguna</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">
                Perbarui informasi akun {{ $user->nama_lengkap }}
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" 
            class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-300 rounded-2xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 hover:text-[#008f5d] transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[2.5rem] border border-emerald-50 shadow-2xl shadow-emerald-100/50 overflow-hidden">
        <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST" class="p-8 md:p-12 space-y-8" autocomplete="off">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Nama Lengkap --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all">
                    </div>
                </div>

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-at text-sm"></i>
                        </div>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all">
                    </div>
                </div>

                {{-- Role --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Hak Akses (Role)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-user-tag text-sm"></i>
                        </div>
                        <select name="role" required class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all appearance-none">
                            <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="pimpinan" {{ $user->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                {{-- Jabatan --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Jabatan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                            <i class="fas fa-briefcase text-sm"></i>
                        </div>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            {{-- Password Section --}}
            <div class="pt-8 border-t border-slate-100">
                <div class="bg-amber-50 rounded-2xl p-4 mb-8 border border-amber-100 text-center">
                    <p class="text-[10px] font-bold text-amber-700 uppercase tracking-widest italic">
                        <i class="fas fa-info-circle mr-2"></i> Biarkan kosong jika tidak ingin mengganti password
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Password Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                                <i class="fas fa-key text-sm"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="w-full pl-12 pr-14 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password', 'toggle-icon-1')" 
                                class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-[#008f5d] transition-colors focus:outline-none">
                                <i id="toggle-icon-1" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-600 uppercase tracking-widest ml-4 italic">Konfirmasi Password Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] transition-colors">
                                <i class="fas fa-shield-check text-sm"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full pl-12 pr-14 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#008f5d]/20 focus:bg-white transition-all placeholder:text-slate-300"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('password_confirmation', 'toggle-icon-2')" 
                                class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-[#008f5d] transition-colors focus:outline-none">
                                <i id="toggle-icon-2" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <a href="{{ route('admin.users.index') }}" class="order-2 md:order-1 flex-1 bg-slate-200 text-slate-700 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] text-center hover:bg-slate-300 transition-all">Batal</a>
                <button type="submit" class="order-1 md:order-2 flex-[2] bg-[#008f5d] text-white py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-emerald-700 hover:shadow-xl hover:shadow-emerald-200 transition-all active:scale-[0.98]">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
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
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection