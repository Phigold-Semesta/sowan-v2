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

        textarea::-webkit-scrollbar {
            width: 4px;
        }
        textarea::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
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
                LPSE Kabupaten Karawang
            </p>
        </div>

        <div class="glass-card rounded-[2.5rem] md:rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden">
            <div class="bg-emerald-900/10 py-4 text-center border-b border-emerald-900/5">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-800">Presensi Tamu Terdaftar</span>
            </div>

            <form action="{{ route('tamu.store') }}" method="POST" class="p-8 md:p-12">
                @csrf
                
                <input type="hidden" name="gmail" value="{{ $tamu->gmail }}">
                <input type="hidden" name="nama_tamu" value="{{ $tamu->nama_tamu }}">
                <input type="hidden" name="is_lama" value="1">

                <div class="mb-10 text-center">
                    <h2 class="text-2xl md:text-3xl font-black text-emerald-950 uppercase tracking-tighter leading-tight">
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
                </div>

                <div class="mt-10">
                    <button type="submit" 
                        class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-[0.3em] rounded-full transition-all shadow-xl hover:shadow-emerald-900/20 active:scale-95 flex items-center justify-center group">
                        <span>Simpan Data</span>
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
                            <div class="flex items-center justify-between p-3 md:p-4 bg-emerald-50/60 border border-emerald-100 rounded-2xl animate-slide-up hover:bg-emerald-50 transition-colors shadow-sm">
                                <div class="flex items-center space-x-3 md:space-x-4 overflow-hidden">
                                    <div class="p-2 bg-white rounded-xl shadow-sm flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-[9px] md:text-[10px] font-black text-emerald-950 uppercase tracking-tight truncate">${doc.nama_dokumen}</p>
                                        <p class="text-[7px] md:text-[8px] text-emerald-600 font-black italic tracking-widest uppercase">Panduan Layanan</p>
                                    </div>
                                </div>
                                <a href="/storage/${doc.file_path}" download="${doc.nama_dokumen}" 
                                   class="p-2 md:p-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all active:scale-90 flex-shrink-0 ml-2">
                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    </script>
</body>
</html>