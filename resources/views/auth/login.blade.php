<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login | SOWAN v2 LPSE Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            background: url("{{ asset('img/batik_emerald_green.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .luxury-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(6, 78, 59, 0.15) 0%, rgba(2, 44, 34, 0.85) 100%);
            backdrop-filter: contrast(1.1) brightness(0.9);
            z-index: 1;
        }
        .light-glow {
            position: absolute; width: min(700px, 90vw); height: min(700px, 90vw);
            background: rgba(52, 211, 153, 0.12);
            filter: blur(130px); border-radius: 50%; z-index: 2;
            animation: move 25s infinite alternate ease-in-out;
        }
        @keyframes move {
            from { transform: translate(-20%, -20%); }
            to { transform: translate(25%, 25%); }
        }
        .reveal-text {
            display: inline-block;
            animation: tracking-in-expand 0.8s cubic-bezier(0.215, 0.610, 0.355, 1.000) both;
        }
        @keyframes tracking-in-expand {
            0% { letter-spacing: -0.2em; opacity: 0; }
            40% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(35px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
    </style>
    <script>
        function switchTab(tab) {
            const pForm = document.getElementById('petugas-form');
            const tForm = document.getElementById('tamu-form');
            const btnP = document.getElementById('btn-petugas');
            const btnT = document.getElementById('btn-tamu');
            const pTitle = document.getElementById('portal-title');
            const pSub = document.getElementById('portal-subtitle');

            if(tab === 'petugas') {
                pForm.classList.remove('hidden');
                tForm.classList.add('hidden');
                pTitle.innerText = "Portal Petugas";
                pSub.innerText = "Authentication Required";
                btnP.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] bg-emerald-400 text-emerald-950 rounded-full shadow-lg transition-all";
                btnT.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition-all";
            } else {
                pForm.classList.add('hidden');
                tForm.classList.remove('hidden');
                pTitle.innerText = "Portal Tamu";
                pSub.innerText = "Guest Access Portal";
                btnT.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] bg-emerald-400 text-emerald-950 rounded-full shadow-lg transition-all";
                btnP.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition-all";
            }
        }
    </script>
</head>
<body class="p-4 sm:p-6">
    <div class="luxury-overlay"></div>
    <div class="light-glow top-0 left-0"></div>
    <div class="light-glow bottom-0 right-0" style="animation-delay: -7s;"></div>

    <div class="w-full max-w-sm md:max-w-md relative z-10">
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl text-white font-black tracking-tighter uppercase reveal-text">SOWAN <span class="text-emerald-400 font-bold ml-2">LPSE</span></h1>
            <p class="text-emerald-50 text-[10px] sm:text-sm font-bold tracking-[0.1em] uppercase italic drop-shadow-lg opacity-90 mt-2">"Sistem Informasi Administrasi Kunjungan"</p>
        </div>

        <div class="glass-card p-6 sm:p-10 rounded-[2rem] sm:rounded-[3rem] relative overflow-hidden">
            <div class="flex bg-black/20 p-1 rounded-full mb-6 sm:mb-8 border border-white/5 relative z-10">
                <button id="btn-petugas" onclick="switchTab('petugas')" class="flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] bg-emerald-400 text-emerald-950 rounded-full shadow-lg transition-all">Petugas</button>
                <button id="btn-tamu" onclick="switchTab('tamu')" class="flex-1 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-white/30 hover:text-white transition-all">Tamu</button>
            </div>

            <div class="relative z-10">
                <div class="flex flex-col items-center justify-center text-center">
                    <h2 id="portal-title" class="text-white text-xl sm:text-2xl font-extrabold mb-1 tracking-tight">Portal Petugas</h2>
                    <p id="portal-subtitle" class="text-emerald-100/50 text-[9px] sm:text-[10px] mb-6 sm:mb-8 tracking-[0.3em] uppercase font-black">Authentication Required</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-3 sm:p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-[10px] sm:text-xs text-center animate-pulse">
                        <ul class="list-none">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                {{-- Form Petugas --}}
                <form id="petugas-form" action="{{ route('login.proses') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    <div class="group">
                        <label class="block text-emerald-200 text-[9px] sm:text-[10px] uppercase font-black tracking-[0.3em] mb-2 ml-4 sm:ml-6 opacity-80">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full px-6 sm:px-8 py-3 sm:py-4 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" placeholder="Ketik username" required>
                    </div>
                    <div class="group">
                        <label class="block text-emerald-200 text-[9px] sm:text-[10px] uppercase font-black tracking-[0.3em] mb-2 ml-4 sm:ml-6 opacity-80">Password</label>
                        <input type="password" name="password" class="w-full px-6 sm:px-8 py-3 sm:py-4 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-3 sm:py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transition-all mt-6 sm:mt-8 text-[10px] sm:text-xs tracking-[0.2em] uppercase">LOG IN</button>
                </form>

                {{-- Form Tamu (DISESUAIKAN: Mengarah ke route check-email) --}}
                <form id="tamu-form" action="{{ route('tamu.check-email') }}" method="POST" class="space-y-4 sm:space-y-6 hidden">
                    @csrf
                    <div class="group">
                        <label for="gmail" class="block text-emerald-200 text-[9px] sm:text-[10px] uppercase font-black tracking-[0.3em] mb-2 ml-4 sm:ml-6 opacity-80">Email Tamu</label>
                        <input type="email" name="gmail" id="gmail" class="w-full px-6 sm:px-8 py-3 sm:py-4 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" placeholder="Contoh: user@gmail.com" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-3 sm:py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transition-all mt-6 sm:mt-8 text-[10px] sm:text-xs tracking-[0.2em] uppercase">LANJUTKAN</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>