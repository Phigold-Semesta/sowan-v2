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

        .star-active {
            color: #10b981 !important;
            filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.6));
            transform: scale(1.1);
        }

        input:focus, select:focus, textarea:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 md:p-12">

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
        @if($gmail)
        <form action="{{ route('tamu.store') }}" method="POST" id="mainForm" class="form-glass rounded-[3rem] md:rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.7)] overflow-hidden border border-white/40 transform transition-all">
            @csrf
            
            {{-- PERBAIKAN: Input hidden untuk identitas alur di controller --}}
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
                                    <option value="Penyedia" {{ old('jenis_tamu') == 'Penyedia' ? 'selected' : '' }}>Penyedia (Vendor)</option>
                                    <option value="Non-Penyedia" {{ old('jenis_tamu') == 'Non-Penyedia' ? 'selected' : '' }}>Non-Penyedia</option>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="group space-y-2">
                            <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Alamat Kantor</label>
                            <input type="text" name="alamat_kantor" value="{{ old('alamat_kantor') }}" placeholder="Lokasi instansi Anda" required 
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-inner">
                        </div>
                    </div>
                </div>

                <div class="my-12 md:my-16 flex items-center">
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
                                <option value="{{ $l->id_layanan }}" data-docs="{{ $l->dokumens->toJson() }}" {{ old('id_layanan') == $l->id_layanan ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                        <div id="file-guide-container" class="mt-4 grid grid-cols-1 gap-3"></div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Petugas Penerima</label>
                        <select name="id_petugas" required class="w-full px-6 py-4 rounded-2xl bg-emerald-50/50 border-2 border-emerald-100 focus:bg-white focus:border-emerald-500 outline-none transition-all font-bold text-sm text-emerald-900 shadow-sm cursor-pointer">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id_petugas }}" {{ old('id_petugas') == $p->id_petugas ? 'selected' : '' }}>{{ $p->nama_petugas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-12 md:mt-16 p-8 md:p-12 border-2 border-dashed border-emerald-100 rounded-[2.5rem] bg-emerald-50/20 group-hover:border-emerald-300 transition-colors">
                    <div class="text-center mb-10">
                        <p class="text-[11px] font-black text-emerald-900 uppercase tracking-[0.3em] mb-6">Bagaimana Pengalaman Anda? (Opsional) ✨</p>
                        <div class="flex justify-center space-x-3 md:space-x-5" id="star-rating">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" data-value="{{ $i }}" class="star-btn transition-all duration-300 hover:scale-150 active:scale-90 outline-none">
                                    <svg class="w-10 h-10 md:w-12 md:h-12 text-gray-200 transition-all pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        {{-- PERBAIKAN: name="skor" agar sesuai dengan validation di controller --}}
                        <input type="hidden" name="skor" id="rating-value" value="0">
                    </div>

                    <div class="group space-y-3">
                        <label class="text-[11px] font-black text-emerald-900 uppercase tracking-widest ml-1">Kesan & Saran</label>
                        {{-- PERBAIKAN: name="komentar" agar sesuai dengan validation di controller --}}
                        <textarea name="komentar" rows="4" placeholder="Berikan masukan berharga Anda untuk layanan kami..."
                            class="w-full px-7 py-6 rounded-[2rem] bg-white border-2 border-emerald-50 focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-sm resize-none">{{ old('komentar') }}</textarea>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="submit" class="w-full py-6 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xl rounded-3xl transition-all shadow-[0_20px_40px_-10px_rgba(5,150,105,0.4)] hover:shadow-[0_25px_50px_-12px_rgba(5,150,105,0.6)] uppercase tracking-[0.2em] flex items-center justify-center space-x-4 group active:scale-95">
                        <span>Simpan Data</span>
                    </button>
                    <p class="text-center text-[10px] text-emerald-900/40 mt-10 uppercase font-black tracking-[0.4em]">
                        SOWAN Ecosystem • LPSE Karawang Digital
                    </p>
                </div>
            </div>
        </form>
        @else
        <div class="max-w-xl mx-auto">
            <form action="{{ route('tamu.check') }}" method="POST" class="form-glass rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.7)] border border-white/40 overflow-hidden">
                @csrf
                <div class="p-10 md:p-20 text-center">
                    <div class="mb-10">
                        <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-emerald-950 uppercase tracking-tighter ">
                            VALIDASI  <span class="text-emerald-500 not-italic tracking-widest ml-1">IDENTITAS</span>
                        </h3>
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em] mt-3 italic leading-relaxed">Masukkan Gmail untuk melanjutkan registrasi</p>
                    </div>
                    <div class="space-y-6">
                        <input type="email" name="gmail" placeholder="nama@gmail.com" required 
                            class="w-full px-8 py-5 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:bg-white focus:border-emerald-500 outline-none transition-all font-bold text-center text-emerald-900 shadow-inner">
                        
                        <button type="submit" class="w-full py-5 bg-emerald-950 hover:bg-black text-white font-black text-sm rounded-2xl transition-all shadow-xl uppercase tracking-widest transform active:scale-95 group flex items-center justify-center space-x-3">
                            <span>Mulai Sekarang</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="mt-12 text-center">
            <p class="text-[9px] text-emerald-100/30 uppercase font-black tracking-[0.6em]">
                © 2026 • LPSE KABUPATEN KARAWANG • SOWAN V2.0
            </p>
        </div>
    </div>

    <script>
        // Logika Dropdown Layanan & Dokumen
        document.getElementById('select-layanan').addEventListener('change', function() {
            const container = document.getElementById('file-guide-container');
            const selectedOption = this.options[this.selectedIndex];
            const docsData = selectedOption.getAttribute('data-docs');

            container.innerHTML = '';

            if (docsData) {
                const dokumens = JSON.parse(docsData);
                if (dokumens.length > 0) {
                    dokumens.forEach(doc => {
                        const fileHtml = `
                            <div class="flex items-center justify-between p-4 md:p-5 bg-emerald-50/60 border border-emerald-100 rounded-2xl animate-slide-up hover:bg-emerald-50 transition-colors shadow-sm">
                                <div class="flex items-center space-x-3 md:space-x-4 overflow-hidden">
                                    <div class="p-2.5 md:p-3 bg-white rounded-xl shadow-sm border border-emerald-50 shrink-0">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] md:text-[11px] font-black text-emerald-950 uppercase tracking-tight truncate">${doc.nama_dokumen}</p>
                                        <p class="text-[8px] md:text-[9px] text-emerald-600 font-black italic tracking-widest uppercase">Persyaratan PDF</p>
                                    </div>
                                </div>
                                <a href="/storage/${doc.file_path}" download="${doc.nama_dokumen}" class="p-2.5 md:p-3.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-md active:scale-90 group shrink-0 ml-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', fileHtml);
                    });
                }
            }
        });

        // Star Rating Logic
        const starBtns = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-value');

        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-value'));
                ratingInput.value = val;
                updateStars(val);
            });
            btn.addEventListener('mouseenter', function() {
                updateStars(parseInt(this.getAttribute('data-value')));
            });
            btn.addEventListener('mouseleave', function() {
                updateStars(parseInt(ratingInput.value));
            });
        });

        function updateStars(value) {
            starBtns.forEach(btn => {
                const btnVal = parseInt(btn.getAttribute('data-value'));
                const svg = btn.querySelector('svg');
                if (btnVal <= value) {
                    svg.classList.add('star-active');
                    svg.classList.remove('text-gray-200');
                } else {
                    svg.classList.remove('star-active');
                    svg.classList.add('text-gray-200');
                }
            });
        }
    </script>
</body>
</html>