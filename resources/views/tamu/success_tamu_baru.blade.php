<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - SOWAN v2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
            background-attachment: fixed;
            overflow-x: hidden;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-card {
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            backdrop-filter: blur(20px);
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: scale(0.9) translateY(40px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .check-wrapper { animation: checkPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both; }
        @keyframes checkPop {
            0% { transform: scale(0); rotate: -45deg; }
            100% { transform: scale(1); rotate: 0deg; }
        }

        .floating-icon { animation: floatingIcon 3s ease-in-out infinite; }
        @keyframes floatingIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(16, 185, 129, 0.1);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: float 10s infinite alternate ease-in-out;
        }

        @keyframes float {
            from { transform: translate(0, 0); }
            to { transform: translate(30px, 50px); }
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #065f46; border-radius: 10px; }
    </style>
</head>
<body class="p-6">
    
    <div class="blob -top-20 -left-20"></div>
    <div class="blob -bottom-20 -right-20" style="background: rgba(52, 211, 153, 0.08); animation-delay: -5s;"></div>

    <div class="max-w-sm md:max-w-xl w-full text-center relative z-10">
        
        <div class="mb-6 md:mb-10">
            <h1 class="text-4xl md:text-6xl font-black italic tracking-tighter text-white leading-none">
                SOWAN <span class="text-emerald-400">V2</span>
            </h1>
            <div class="flex items-center justify-center space-x-3 mt-3 opacity-50">
                <div class="h-[1px] w-6 bg-emerald-400"></div>
                <p class="text-[8px] md:text-xs font-black uppercase tracking-[0.4em] text-emerald-100">
                    LPSE Kabupaten Karawang
                </p>
                <div class="h-[1px] w-6 bg-emerald-400"></div>
            </div>
        </div>

        <div class="success-card bg-white rounded-[2.5rem] md:rounded-[4rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.8)] p-8 md:p-16 relative overflow-hidden border border-white/40">
            
            <div class="mb-6 md:mb-8 flex justify-center">
                <div class="relative check-wrapper">
                    <div class="floating-icon">
                        <div class="absolute inset-0 bg-emerald-500 blur-3xl opacity-20 animate-pulse"></div>
                        <div class="relative bg-gradient-to-br from-emerald-400 to-emerald-600 text-white p-5 md:p-8 rounded-full shadow-2xl shadow-emerald-500/40">
                            <svg class="w-10 h-10 md:w-14 md:h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-2xl md:text-4xl font-black text-emerald-950 uppercase tracking-tighter mb-1 leading-tight">
                Kunjungan <br><span class="text-emerald-500 italic font-medium">Tercatat!</span>
            </h2>

            <div class="my-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                <p class="text-[9px] md:text-xs font-bold text-emerald-600 uppercase tracking-[0.2em] mb-1">Nomor Antrean Anda</p>
                <h3 class="text-5xl md:text-6xl font-black text-emerald-900 tracking-tight">{{ session('antrean', '-') }}</h3>
            </div>
            
            <p class="text-gray-500 text-[10px] md:text-sm font-bold max-w-[200px] md:max-w-xs mx-auto leading-relaxed mb-8 md:mb-10 uppercase tracking-wide">
                Terima kasih, <span class="text-emerald-700 italic">{{ $nama_tamu ?? 'Tamu Terhormat' }}</span>. Data Anda telah tersimpan dalam sistem SOWAN.
            </p>

            <div class="space-y-4">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center w-full py-4 md:py-6 bg-emerald-900 hover:bg-black text-white font-black text-[9px] md:text-xs uppercase tracking-[0.3em] rounded-xl md:rounded-3xl transition-all shadow-xl hover:shadow-emerald-950/20 group active:scale-95">
                    <svg class="w-4 h-4 mr-3 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Selesai & Keluar</span>
                </a>
            </div>

            <div class="mt-8 md:mt-12 flex items-center justify-center space-x-3 opacity-30">
                <div class="h-[1px] w-6 bg-emerald-900"></div>
                <p class="text-[7px] md:text-[8px] font-black uppercase tracking-widest text-emerald-900">Secure Digital Record</p>
                <div class="h-[1px] w-6 bg-emerald-900"></div>
            </div>
        </div>

        <p class="mt-8 md:mt-12 text-[8px] md:text-[10px] text-emerald-100/20 uppercase font-black tracking-[0.5em]">
            Verified Guest Management System
        </p>
    </div>

    <script>
        window.onload = function() {
            const duration = 3 * 1000;
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 2,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#10b981', '#059669', '#34d399']
                });
                confetti({
                    particleCount: 2,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#10b981', '#059669', '#34d399']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        };
    </script>
</body>
</html>