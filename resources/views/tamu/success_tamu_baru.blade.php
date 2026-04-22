<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - SOWAN v2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            overflow: hidden;
        }
        .success-animate {
            animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    
    <div class="blob -top-20 -left-20"></div>
    <div class="blob -bottom-20 -right-20" style="background: rgba(52, 211, 153, 0.05);"></div>

    <div class="max-w-2xl w-full text-center success-animate">
        <div class="mb-10">
            <h1 class="text-5xl md:text-7xl font-black italic tracking-tighter text-white leading-none">
                SOWAN <span class="text-emerald-400">V2</span>
            </h1>
            <p class="text-[10px] md:text-xs font-extrabold uppercase tracking-[0.6em] text-emerald-100/60 mt-3">
                LPSE Kabupaten Karawang
            </p>
        </div>

        <div class="bg-white rounded-[3rem] md:rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.7)] p-10 md:p-20 relative overflow-hidden border border-white/20">
            <div class="mb-8 flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-emerald-500 blur-2xl opacity-20 animate-pulse"></div>
                    <div class="relative bg-emerald-500 text-white p-6 rounded-full shadow-xl shadow-emerald-500/20">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <h2 class="text-3xl md:text-4xl font-black text-emerald-900 uppercase tracking-tighter mb-4 leading-tight">
                Data Berhasil <br><span class="text-emerald-500 italic font-medium">Disimpan!</span>
            </h2>
            
            <p class="text-gray-500 text-sm md:text-base font-medium max-w-sm mx-auto leading-relaxed mb-12">
                Terima kasih, <span class="text-emerald-800 font-bold italic">{{ $nama_tamu ?? 'Tamu' }}</span>. Data kunjungan Anda telah tercatat secara digital di sistem kami.
            </p>

            <div class="space-y-4">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm uppercase tracking-widest rounded-2xl transition-all shadow-lg hover:shadow-emerald-500/30 group active:scale-95">
                    <span>Kembali ke Beranda</span>
                    <svg class="w-4 h-4 ml-3 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>

            <div class="mt-12 flex items-center justify-center space-x-3 opacity-20">
                <div class="h-[1px] w-8 bg-emerald-900"></div>
                <p class="text-[8px] font-bold uppercase tracking-widest text-emerald-900">Verified System</p>
                <div class="h-[1px] w-8 bg-emerald-900"></div>
            </div>
        </div>

        <p class="mt-10 text-[9px] md:text-[10px] text-emerald-200/40 uppercase font-bold tracking-[0.4em]">
            Digital Guest Experience by LPSE Karawang
        </p>
    </div>

</body>
</html>