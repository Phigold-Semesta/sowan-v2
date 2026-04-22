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
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #064e3b; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .star-active {
            color: #34d399 !important;
            filter: drop-shadow(0 0 8px rgba(52, 211, 153, 0.5));
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">

    <div class="mb-8 md:mb-10 text-center w-full">
        <div class="relative inline-block">
            <div class="absolute -inset-6 bg-emerald-400/20 blur-3xl rounded-full"></div>
            <h1 class="relative text-4xl md:text-6xl font-black italic tracking-tighter text-white leading-none">
                SOWAN <span class="text-emerald-400">V2</span>
            </h1>
            <div class="relative mt-2">
                <p class="text-[9px] md:text-xs font-extrabold uppercase tracking-[0.4em] md:tracking-[0.6em] text-emerald-100/80 ml-1">
                    LPSE Kabupaten Karawang
                </p>
            </div>
            <div class="flex items-center justify-center space-x-3 mt-4">
                <div class="h-[1px] w-8 md:w-10 bg-gradient-to-r from-transparent to-emerald-500/50"></div>
                <p class="text-[8px] md:text-[9px] uppercase font-bold tracking-[0.3em] text-emerald-200/40">Digital Guest Book</p>
                <div class="h-[1px] w-8 md:w-10 bg-gradient-to-l from-transparent to-emerald-500/50"></div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-4xl mx-auto">
        @if($gmail)
        <form action="{{ route('tamu.store') }}" method="POST" id="mainForm" class="bg-white rounded-[2.5rem] md:rounded-[3.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.6)] overflow-hidden border border-white/20 transform transition-all">
            @csrf
            
            <div class="p-6 md:p-14">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 md:mb-12 gap-4">
                    <div>
                        <h3 class="text-xl md:text-2xl font-black text-emerald-900 uppercase tracking-tighter">
                            Registrasi <span class="text-emerald-500 font-medium italic text-lg md:text-2xl">Tamu Baru</span>
                        </h3>
                        <p class="text-gray-400 text-[10px] md:text-xs font-medium mt-1">Lengkapi data untuk kunjungan perdana Anda.</p>
                    </div>
                    <div class="inline-flex items-center bg-emerald-50 px-4 py-2 md:px-5 md:py-2.5 rounded-xl md:rounded-2xl border border-emerald-100 self-start md:self-auto">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-3"></div>
                        <span class="text-[9px] md:text-[10px] font-bold text-emerald-800 italic truncate max-w-[200px]">
                            {{ $gmail }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                    <div class="space-y-5 md:space-y-6">
                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" placeholder="Nama sesuai KTP" required 
                                class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm">
                        </div>

                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                            <input type="tel" name="no_wa" value="{{ old('no_wa') }}" placeholder="0812XXXXXXXX" required 
                                class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm">
                        </div>

                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Hadir Sebagai</label>
                            <input type="text" name="hadir_sebagai" value="{{ old('hadir_sebagai') }}" placeholder="Contoh: Peserta Tender" required 
                                class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-5 md:space-y-6">
                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Asal Instansi</label>
                            <input type="text" name="nama_instansi" value="{{ old('nama_instansi') }}" placeholder="Contoh: PT. Teknologi Bangsa" required 
                                class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm">
                        </div>

                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Keterangan Tamu</label>
                            <div class="relative">
                                <select name="jenis_tamu" required class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 outline-none transition-all font-semibold text-sm appearance-none shadow-sm cursor-pointer">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Penyedia" {{ old('jenis_tamu') == 'Penyedia' ? 'selected' : '' }}>Penyedia</option>
                                    <option value="Non-Penyedia" {{ old('jenis_tamu') == 'Non-Penyedia' ? 'selected' : '' }}>Non-Penyedia</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="group space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Alamat Kantor</label>
                            <input type="text" name="alamat_kantor" value="{{ old('alamat_kantor') }}" placeholder="Alamat lengkap instansi..." required 
                                class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="my-8 md:my-12 flex items-center">
                    <div class="flex-grow h-[1px] bg-emerald-100"></div>
                    <span class="px-4 text-[9px] md:text-[10px] font-bold text-emerald-300 uppercase tracking-widest text-center">Tujuan Kunjungan</span>
                    <div class="flex-grow h-[1px] bg-emerald-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Layanan</label>
                        <select id="select-layanan" name="id_layanan" required class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-emerald-50/50 border-2 border-emerald-100 focus:bg-white focus:border-emerald-500 outline-none transition-all font-semibold text-sm shadow-sm">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id_layanan }}" data-docs="{{ $l->dokumens->toJson() }}" {{ old('id_layanan') == $l->id_layanan ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }}
                                </option>
                            @endforeach
                        </select>
                        <div id="file-guide-container" class="mt-4 space-y-2"></div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Petugas</label>
                        <select name="id_petugas" required class="w-full px-5 py-3.5 md:px-6 md:py-4 rounded-xl md:rounded-2xl bg-emerald-50/50 border-2 border-emerald-100 focus:bg-white focus:border-emerald-500 outline-none transition-all font-semibold text-sm shadow-sm">
                            <option value="">-- Pilih Petugas --</option>
                            @foreach($petugas as $p)
                                <option value="{{ $p->id_petugas }}" {{ old('id_petugas') == $p->id_petugas ? 'selected' : '' }}>{{ $p->nama_petugas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="hidden" name="gmail" value="{{ $gmail }}">

                <div class="mt-10 md:mt-12 p-6 md:p-10 border-2 border-dashed border-emerald-100 rounded-[2rem] md:rounded-[2.5rem] bg-emerald-50/30">
                    <div class="text-center mb-8">
                        <p class="text-[9px] md:text-[11px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-4">Rating Layanan (Opsional) ✨</p>
                        <div class="flex justify-center space-x-2 md:space-x-4" id="star-rating">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" data-value="{{ $i }}" class="star-btn transition-transform hover:scale-125 active:scale-90 outline-none">
                                    <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-200 transition-colors pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="0">
                    </div>

                    <div class="group space-y-2">
                        <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Kesan & Saran</label>
                        <textarea name="saran" rows="4" placeholder="Tuliskan pengalaman atau saran Anda..."
                            class="w-full px-5 py-4 md:px-7 md:py-5 rounded-2xl md:rounded-[2rem] bg-white border-2 border-emerald-50 focus:border-emerald-500 focus:ring-0 outline-none transition-all placeholder-gray-300 font-semibold text-sm shadow-sm resize-none">{{ old('saran') }}</textarea>
                    </div>
                </div>

                <div class="mt-10 md:mt-12">
                    <button type="submit" class="w-full py-5 md:py-6 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-lg md:text-xl rounded-2xl md:rounded-3xl transition-all shadow-xl hover:shadow-emerald-500/40 uppercase tracking-widest flex items-center justify-center space-x-3 group active:scale-[0.95]">
                        <span>Simpan Data</span>
                        <svg class="w-5 h-5 md:w-6 md:h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </button>
                    <p class="text-center text-[8px] md:text-[10px] text-emerald-900/30 mt-6 md:mt-8 uppercase font-bold tracking-[0.2em]">
                        LPSE Karawang • SOWAN Ecosystem
                    </p>
                </div>
            </div>
        </form>
        @else
        <div class="max-w-md mx-auto">
            <form action="{{ route('tamu.check') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.6)] overflow-hidden border border-white/20">
                @csrf
                <div class="p-8 md:p-16">
                    <div class="mb-8 text-center">
                        <h3 class="text-xl md:text-2xl font-black text-emerald-900 uppercase tracking-tighter">Validasi <span class="text-emerald-500 italic font-medium">Identitas</span></h3>
                        <p class="text-gray-400 text-[9px] md:text-[10px] font-bold uppercase tracking-widest mt-2 italic">Gunakan Gmail untuk memulai</p>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] md:text-[11px] font-black text-emerald-800 uppercase tracking-widest ml-1">Alamat Gmail</label>
                            <input type="email" name="gmail" placeholder="contoh@gmail.com" required 
                                class="w-full px-5 py-4 md:px-6 md:py-5 rounded-xl md:rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-0 outline-none transition-all font-semibold text-sm shadow-sm">
                        </div>
                        <button type="submit" class="w-full py-4 md:py-5 bg-emerald-900 hover:bg-black text-white font-black text-xs md:text-sm rounded-xl md:rounded-2xl transition-all shadow-xl uppercase tracking-widest transform active:scale-95">
                            Cek Identitas
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="mt-8 md:mt-12 text-center">
            <p class="text-[8px] md:text-[10px] text-emerald-200/40 uppercase font-bold tracking-[0.3em] md:tracking-[0.5em]">
                © 2026 • SOWAN V2 DIGITAL SYSTEM
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
                            <div class="flex items-center justify-between p-4 bg-emerald-50/80 border border-emerald-100 rounded-2xl animate-slide-up shadow-sm">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2.5 bg-white rounded-xl shadow-sm border border-emerald-50">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="max-w-[120px] md:max-w-[200px]">
                                        <p class="text-[10px] font-black text-emerald-900 uppercase tracking-tight truncate">${doc.nama_dokumen}</p>
                                        <p class="text-[8px] text-emerald-600 font-bold italic tracking-wider">PDF Panduan</p>
                                    </div>
                                </div>
                                <a href="/storage/${doc.file_path}" download="${doc.nama_dokumen}" class="p-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-md active:scale-90 group">
                                    <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        // Rating Bintang
        const starBtns = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-value');

        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                ratingInput.value = value;
                updateStars(value);
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
                const btnValue = parseInt(btn.getAttribute('data-value'));
                const svg = btn.querySelector('svg');
                if (btnValue <= value) {
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