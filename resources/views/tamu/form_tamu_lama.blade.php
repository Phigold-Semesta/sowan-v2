<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunjungan Tamu Lama - SOWAN v2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top left, #064e3b 0%, #022c22 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .input-pill {
            border-radius: 9999px;
            transition: all 0.3s ease;
        }

        .star-active {
            color: #10b981 !important;
            filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.6));
            transform: scale(1.1);
        }

        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(16, 185, 129, 0.15);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }

        .animate-slide-up {
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="p-4 md:p-10">

    <div class="blob top-0 left-0"></div>
    <div class="blob bottom-0 right-0" style="background: rgba(52, 211, 153, 0.1);"></div>

    <div class="max-w-2xl w-full relative z-10 animate-slide-up">
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter text-white leading-none">
                SOWAN <span class="text-emerald-400">V2</span>
            </h1>
            <p class="text-[10px] md:text-xs font-black uppercase tracking-[0.4em] text-emerald-100/60 mt-2">
                LPSE Kabupaten Karawang [cite: 2026-03-24]
            </p>
        </div>

        <div class="glass-card rounded-[2.5rem] md:rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden">
            <div class="bg-emerald-900/10 py-4 text-center border-b border-emerald-900/5">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-800">Presensi Tamu Terdaftar</span>
            </div>

            <form action="{{ route('tamu.store') }}" method="POST" class="p-8 md:p-12">
                @csrf
                
                <input type="hidden" name="gmail" value="{{ $tamu->gmail }}">
                <input type="hidden" name="tipe_tamu" value="lama"> <div class="mb-10 text-center">
                    <h2 class="text-2xl md:text-3xl font-black text-emerald-950 uppercase tracking-tighter">
                        Selamat Datang Kembali, <br>
                        <span class="text-emerald-600 italic font-medium">{{ $tamu->nama_tamu }}</span>
                    </h2>
                    <p class="text-gray-400 text-[10px] font-bold mt-2 uppercase tracking-widest leading-relaxed">
                        Identitas terverifikasi: {{ $tamu->nama_instansi }}
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-900 mb-2 ml-4">Layanan LPSE</label>
                            <div class="relative">
                                <select name="id_layanan" id="select-layanan" required
                                    class="w-full px-6 py-4 bg-emerald-50/50 border-2 border-transparent focus:bg-white focus:border-emerald-500 text-emerald-950 font-bold text-sm input-pill appearance-none cursor-pointer transition-all">
                                    <option value="" disabled selected>Pilih Layanan</option>
                                    @foreach($layanan as $l)
                                        <option value="{{ $l->id_layanan }}" data-docs="{{ $l->dokumens->toJson() }}">{{ $l->nama_layanan }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-900 mb-2 ml-4">Petugas Tujuan</label>
                            <div class="relative">
                                <select name="id_petugas" required
                                    class="w-full px-6 py-4 bg-emerald-50/50 border-2 border-transparent focus:bg-white focus:border-emerald-500 text-emerald-950 font-bold text-sm input-pill appearance-none cursor-pointer transition-all">
                                    <option value="" disabled selected>Pilih Petugas</option>
                                    @foreach($petugas as $p)
                                        <option value="{{ $p->id_petugas }}">{{ $p->nama_petugas }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="file-guide-container" class="space-y-3"></div>

                    <div class="py-4 flex items-center">
                        <div class="flex-grow h-[1px] bg-emerald-100"></div>
                        <span class="px-4 text-[9px] font-black text-emerald-300 uppercase tracking-widest">Feedback Anda</span>
                        <div class="flex-grow h-[1px] bg-emerald-100"></div>
                    </div>

                    <div class="p-6 md:p-8 border-2 border-dashed border-emerald-100 rounded-[2.5rem] bg-emerald-50/20">
                        <div class="text-center mb-6">
                            <p class="text-[10px] font-black text-emerald-900 uppercase tracking-widest mb-4">Berikan Rating Layanan ✨ [cite: 2026-03-14]</p>
                            <div class="flex justify-center space-x-2 md:space-x-4" id="star-rating">
                                @for($i=1; $i<=5; $i++)
                                    <button type="button" data-value="{{ $i }}" class="star-btn transition-all duration-300 hover:scale-125 active:scale-90 outline-none">
                                        <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-200 transition-all pointer-events-none" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="skor" id="rating-value" value="0">
                        </div>

                        <div class="group space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-900 ml-4">Kritik & Saran (Opsional)</label>
                            <textarea name="komentar" rows="3" 
                                class="w-full px-6 py-4 rounded-[1.5rem] bg-white border-2 border-emerald-50 focus:border-emerald-500 outline-none transition-all placeholder-gray-300 font-bold text-sm text-emerald-950 shadow-sm resize-none"
                                placeholder="Tuliskan masukan Anda di sini...">{{ old('komentar') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" 
                        class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-[0.3em] rounded-full transition-all shadow-xl hover:shadow-emerald-900/20 active:scale-95 flex items-center justify-center group">
                        <span>Konfirmasi Kedatangan</span>
                        <svg class="w-4 h-4 ml-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                    
                    <a href="{{ route('tamu.index') }}" class="block text-center mt-6 text-[9px] font-black uppercase tracking-widest text-emerald-900/40 hover:text-emerald-900 transition-colors">
                        Batal & Kembali
                    </a>
                </div>
            </form>

            <div class="bg-emerald-950 py-3 text-center">
                <p class="text-[7px] font-black uppercase tracking-[0.5em] text-emerald-100/30">Secure Digital Record System • SOWAN v2.0</p>
            </div>
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
                            <div class="flex items-center justify-between p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl animate-slide-up hover:bg-emerald-50 transition-colors shadow-sm">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-white rounded-xl shadow-sm">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="max-w-[200px] md:max-w-[300px]">
                                        <p class="text-[10px] font-black text-emerald-950 uppercase tracking-tight truncate">${doc.nama_dokumen}</p>
                                        <p class="text-[8px] text-emerald-600 font-black italic tracking-widest uppercase">Panduan Layanan</p>
                                    </div>
                                </div>
                                <a href="/storage/${doc.file_path}" download="${doc.nama_dokumen}" class="p-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all active:scale-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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