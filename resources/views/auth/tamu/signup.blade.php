<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Registrasi Tamu | SOWAN v2 LPSE Karawang</title>
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
            <div class="relative z-10">
                <div class="flex flex-col items-center justify-center text-center mb-6">
                    <h2 class="text-white text-xl sm:text-2xl font-extrabold mb-1 tracking-tight">Registrasi Tamu</h2>
                    <p class="text-emerald-100/50 text-[9px] sm:text-[10px] tracking-[0.3em] uppercase font-black">Buat Akun Resmi Baru</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-3 sm:p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-[10px] sm:text-xs text-center animate-pulse">
                        <ul class="list-none">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('tamu.register.store') }}" method="POST" class="space-y-3 sm:space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Nama Lengkap</label>
                            <input type="text" name="nama_tamu" value="{{ old('nama_tamu') }}" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Gmail</label>
                            <input type="email" name="gmail" value="{{ old('gmail') }}" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Password</label>
                            <input type="password" name="password" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">No. WA</label>
                                <input type="text" name="no_wa" value="{{ old('no_wa') }}" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" required>
                            </div>
                            <div>
                                <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Jenis</label>
                                <select name="jenis_tamu" class="w-full px-4 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold">
                                    <option value="Penyedia">Penyedia</option>
                                    <option value="Non-Penyedia">Non-Penyedia</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Instansi</label>
                            <input type="text" name="nama_instansi" value="{{ old('nama_instansi') }}" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold">
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Hadir Sebagai</label>
                            <input type="text" name="hadir_sebagai" value="{{ old('hadir_sebagai') }}" class="w-full px-6 py-3 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-emerald-200 text-[9px] uppercase font-black tracking-[0.2em] mb-1 ml-4 opacity-80">Alamat Kantor</label>
                            <textarea name="alamat_kantor" class="w-full px-6 py-3 bg-white border border-white/10 rounded-3xl focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" rows="2" required>{{ old('alamat_kantor') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-3 sm:py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transition-all mt-4 text-[10px] sm:text-xs tracking-[0.2em] uppercase">DAFTAR AKUN</button>
                    
                    <div class="text-center mt-2">
                        {{-- Rute telah diperbaiki menjadi tamu.login.view --}}
                        <a href="{{ route('tamu.login.view') }}" class="text-emerald-300 text-[9px] font-bold uppercase tracking-[0.1em] hover:text-white transition-colors">Sudah punya akun? Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>