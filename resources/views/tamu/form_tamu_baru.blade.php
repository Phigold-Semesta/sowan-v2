<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Tamu Baru - SOWAN v2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top left, #064e3b 0%, #022c22 100%);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #022c22; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }

        .form-glass {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
        }

        .animate-slide-up {
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input:focus, select:focus, textarea:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 md:p-12">

    {{-- Notifikasi Error --}}
    @if(session('error'))
    <div class="fixed top-5 right-5 z-50 animate-slide-up">
        <div class="bg-red-500 text-white px-6 py-4 rounded-2xl shadow-2xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="mb-10 md:mb-14 text-center w-full animate-slide-up">
        <div class="relative inline-block">
            <div class="absolute -inset-10 bg-emerald-500/20 blur-[100px] rounded-full"></div>
            <h1 class="relative text-5xl md:text-7xl font-black italic tracking-tighter text-white leading-none">
                SOWAN <span class="text-emerald-400">V2</span>
            </h1>
            <div class="relative mt-3">
                <p class="text-[10px] md:text-xs font-black uppercase tracking-[0.5em] text-emerald-100/90 ml-1">
                    LPSE Kabupaten Karawang
                </p>
            </div>
            <div class="flex items-center justify-center space-x-4 mt-6 opacity-60">
                <div class="h-[1px] w-12 bg-gradient-to-r from-transparent to-emerald-400"></div>
                <p class="text-[9px] uppercase font-bold tracking-[0.4em] text-emerald-300">Digital Guest Book</p>
                <div class="h-[1px] w-12 bg-gradient-to-l from-transparent to-emerald-400"></div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-5xl mx-auto animate-slide-up" style="animation-delay: 0.1s">
        {{-- Form Registrasi Utama --}}
        <form action="{{ route('tamu.store') }}" method="POST" id="mainForm" class="form-glass rounded-[3rem] md:rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.7)] overflow-hidden border border-white/40 transform transition-all">
            @csrf
            
            <input type="hidden" name="tipe_tamu" value="baru">
            <input type="hidden" name="gmail" value="{{ $gmail }}">

            <div class="p-8 md:p-16">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 md:mb-16 gap-6">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-black text-emerald-950 uppercase tracking-tighter">
                            Registrasi <span class="text-emerald-500 font-medium italic">Tamu Baru</span>
                        </h3>
                        <p class="text-gray-400 text-xs md:text-sm font-semibold mt-1">Satu identitas untuk kemudahan kunjungan berikutnya.</p>
                    </div>
                    <div class="inline-flex items-center bg-emerald-50 px-5 py-3 rounded-2xl border border-emerald-100 shadow-sm self-start md:self-auto">
                        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse mr-3"></div>
                        <span class="text-xs font-black text-emerald-800 italic truncate max-w-[220px]">
                            {{ $gmail }}
                        </span>
                    </div>
                </div>

                {{-- Grid Form --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
                    <div class="space-y-6">
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" placeholder="Input nama sesuai identitas" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                            <input type="tel" name="no_wa" value="{{ old('no_wa') }}" placeholder="08XXXXXXXXXX" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Hadir Sebagai</label>
                            <input type="text" name="hadir_sebagai" value="{{ old('hadir_sebagai') }}" placeholder="Misal: Direktur, Peserta Tender" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Asal Instansi</label>
                            <input type="text" name="nama_instansi" value="{{ old('nama_instansi') }}" placeholder="Nama Perusahaan / Instansi" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Jenis Tamu</label>
                            <div class="relative">
                                <select name="jenis_tamu" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all font-bold text-sm text-emerald-950 appearance-none cursor-pointer shadow-inner">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Penyedia">Penyedia (Vendor)</option>
                                    <option value="Non-Penyedia">Non-Penyedia</option>
                                </select>
                            </div>
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Alamat Kantor</label>
                            <input type="text" name="alamat_kantor" value="{{ old('alamat_kantor') }}" placeholder="Lokasi instansi Anda" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                    </div>
                </div>

                {{-- Detail Kunjungan --}}
                <div class="my-12 flex items-center">
                    <div class="flex-grow h-[1px] bg-emerald-100"></div>
                    <span class="px-6 text-[10px] font-black text-emerald-300 uppercase tracking-[0.3em] text-center">Detail Kunjungan Hari Ini</span>
                    <div class="flex-grow h-[1px] bg-emerald-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Layanan yang Dituju</label>
                        <select id="select-layanan" name="id_layanan" required class="w-full px-6 py-4 rounded-2xl bg-emerald-50/50 border-2 border-emerald-100 focus:bg-white focus:border-emerald-500 outline-none transition-all font-bold text-sm text-emerald-900 shadow-sm cursor-pointer">
                            <option value="">-- Pilih Layanan LPSE --</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id_layanan }}" data-docs="{{ $l->dokumens->toJson() }}">{{ $l->nama_layanan }}</option>
                            @endforeach
                        </select>
                        <div id="file-guide-container" class="mt-4 grid grid-cols-1 gap-3"></div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Petugas Penerima</label>
                        <select name="id_petugas" required class="w-full px-6 py-4 rounded-2xl bg-emerald-50/50 border-2 border-emerald-100 focus:bg-white focus:border-emerald-500 outline-none transition-all font-bold text-sm text-emerald-900 shadow-sm cursor-pointer">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id_petugas }}">{{ $p->nama_petugas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="submit" class="w-full py-6 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xl rounded-3xl transition-all shadow-xl uppercase tracking-[0.2em] active:scale-95">
                        Simpan Data
                    </button>
                    <a href="{{ route('tamu.index') }}" class="block text-center mt-6 text-[9px] font-black uppercase tracking-widest text-emerald-900/40 hover:text-emerald-900 transition-colors">
                        Batal & Kembali
                    </a>
                </div>
            </div>
        </form>

        <div class="mt-12 text-center">
            <p class="text-[9px] text-emerald-100/30 uppercase font-black tracking-[0.6em]">© 2026 • LPSE KABUPATEN KARAWANG • SOWAN V2.0</p>
        </div>
    </div>

    <script>
        document.getElementById('select-layanan').addEventListener('change', function() {
            const container = document.getElementById('file-guide-container');
            const selectedOption = this.options[this.selectedIndex];
            const docsData = selectedOption.getAttribute('data-docs');
            container.innerHTML = '';
            if (docsData) {
                const dokumens = JSON.parse(docsData);
                dokumens.forEach(doc => {
                    container.insertAdjacentHTML('beforeend', `
                        <div class="flex items-center justify-between p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl shadow-sm">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="p-2 bg-white rounded-xl shadow-sm border border-emerald-50"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                                <p class="text-[10px] font-black text-emerald-950 uppercase truncate">${doc.nama_dokumen}</p>
                            </div>
                        </div>`);
                });
            }
        });
    </script>
</body>
</html>