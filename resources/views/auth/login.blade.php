<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SOWAN v2 LPSE Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            /* PERBAIKAN: Langsung arahkan ke folder di dalam public, tanpa kata 'public' */
            background: url("{{ asset('img/batik_emerald_green.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Overlay Gradasi Emerald untuk memperkuat kesan mewah */
        .luxury-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(6, 78, 59, 0.4) 0%, rgba(6, 78, 59, 0.9) 100%);
            z-index: 1;
        }

        /* Animasi cahaya lembut agar tidak statis */
        .light-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(52, 211, 153, 0.15);
            filter: blur(120px);
            border-radius: 50%;
            z-index: 2;
            animation: move 20s infinite alternate ease-in-out;
        }

        @keyframes move {
            from { transform: translate(-15%, -15%); }
            to { transform: translate(20%, 20%); }
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

        /* Glassmorphism yang disempurnakan */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="p-6">

    <div class="luxury-overlay"></div>
    <div class="light-glow top-0 left-0"></div>
    <div class="light-glow bottom-0 right-0" style="animation-delay: -5s;"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div class="flex items-center bg-emerald-950/90 backdrop-blur-2xl px-8 py-3 rounded-full border border-white/20 shadow-2xl">
                    <h1 class="text-white text-3xl font-black tracking-tight uppercase reveal-text">
                        SOWAN <span class="text-emerald-400 font-bold ml-1">LPSE</span>
                    </h1>
                    <div class="ml-4 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <p class="text-white/90 text-sm font-semibold tracking-wide italic" style="text-shadow: 0 4px 12px rgba(0,0,0,0.6);">
                "Sistem Informasi Administrasi Kunjungan"
            </p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-400/10 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex flex-col items-center justify-center text-center">
                    <h2 class="text-white text-2xl font-bold mb-1 tracking-tight">Portal Petugas</h2>
                    <p class="text-emerald-100/60 text-xs mb-8 tracking-wide uppercase font-medium">Authentication required</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/40 text-red-100 text-xs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="group">
                        <label for="username" class="block text-white text-[10px] uppercase font-bold tracking-[0.3em] mb-2 ml-5 opacity-80">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" 
                            class="w-full px-7 py-4 bg-white/5 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/10 transition-all duration-300 text-white text-sm placeholder-white/20"
                            placeholder="Ketik username" required autofocus>
                    </div>

                    <div class="group">
                        <label for="password" class="block text-white text-[10px] uppercase font-bold tracking-[0.3em] mb-2 ml-5 opacity-80">Password</label>
                        <input type="password" name="password" id="password" 
                            class="w-full px-7 py-4 bg-white/5 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/10 transition-all duration-300 text-white text-sm placeholder-white/20"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-4 rounded-full shadow-[0_10px_40px_rgba(52,211,153,0.4)] transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 mt-6 text-sm tracking-widest uppercase">
                        LOG IN
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-12 space-y-3">
            <p class="text-white/40 text-[9px] font-bold tracking-[0.5em] uppercase">
                LPSE Kabupaten Karawang
            </p>
            <div class="h-[1px] w-12 bg-white/20 mx-auto"></div>
        </div>
    </div>

</body>
</html>