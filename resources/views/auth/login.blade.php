<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SOWAN v2 LPSE Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.2);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: move 25s infinite alternate ease-in-out;
        }

        @keyframes move {
            from { transform: translate(-5%, -5%); }
            to { transform: translate(15%, 15%); }
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
    </style>
</head>
<body class="bg-emerald-800 min-h-screen flex items-center justify-center p-6 overflow-hidden relative">

    <div class="blob top-0 left-0" style="background: rgba(52, 211, 153, 0.2);"></div>
    <div class="blob bottom-0 right-0" style="animation-delay: -5s; background: rgba(110, 231, 183, 0.15);"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-white rounded-xl shadow-xl transform rotate-6 hover:rotate-0 transition-transform duration-500 z-20">
                    <span class="text-emerald-600 font-extrabold text-3xl">S</span>
                </div>
                
                <div class="flex items-center bg-emerald-900/40 backdrop-blur-md pl-6 pr-6 py-2 rounded-r-full -ml-4 z-10 border-y border-r border-white/20">
                    <h1 class="text-white text-3xl font-black tracking-tight uppercase reveal-text">
                        OWAN <span class="text-emerald-300">v2</span>
                    </h1>
                    <div class="ml-3 text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <p class="text-emerald-100/80 text-sm font-light tracking-wide italic">"Sistem Informasi Administrasi Kunjungan"</p>
        </div>

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/5 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex flex-col items-center justify-center text-center">
                    <h2 class="text-white text-xl font-semibold mb-1 tracking-tight">Portal Petugas</h2>
                    <p class="text-emerald-50/60 text-xs mb-8 tracking-wide">Gunakan kredensial Anda untuk masuk</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-xs animate-pulse">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="group">
                        <label for="username" class="block text-emerald-50 text-[10px] uppercase font-bold tracking-[0.2em] mb-2 ml-5">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" 
                            class="w-full px-7 py-4 bg-white/20 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:bg-white/30 transition-all text-white placeholder-emerald-100/30 text-sm"
                            placeholder="Ketik username" required>
                    </div>

                    <div class="group">
                        <label for="password" class="block text-emerald-50 text-[10px] uppercase font-bold tracking-[0.2em] mb-2 ml-5">Password</label>
                        <input type="password" name="password" id="password" 
                            class="w-full px-7 py-4 bg-white/20 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:bg-white/30 transition-all text-white placeholder-emerald-100/30 text-sm"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-bold py-4 rounded-full shadow-[0_10px_25px_rgba(52,211,153,0.3)] transform hover:translate-y-[-2px] active:translate-y-[0px] transition-all duration-300 mt-6 text-sm tracking-widest uppercase">
                        LOGIN
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-12 space-y-2">
            <p class="text-emerald-100/30 text-[10px] tracking-[0.4em] uppercase">
                LPSE Kabupaten Karawang
            </p>
            <div class="h-[1px] w-12 bg-white/10 mx-auto"></div>
        </div>
    </div>

</body>
</html>