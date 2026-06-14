<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Publik | SOWAN v2 LPSE Karawang</title>
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

    <div class="w-full max-w-sm md:max-w-md relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl text-white font-black tracking-tighter uppercase">SOWAN <span class="text-emerald-400 font-bold ml-2">LPSE</span></h1>
            <p class="text-emerald-50 text-[10px] sm:text-sm font-bold tracking-[0.1em] uppercase italic opacity-90 mt-2">"Sistem Informasi Administrasi Kunjungan"</p>
        </div>

        <div class="glass-card p-8 sm:p-10 rounded-[2rem] relative overflow-hidden">
            <div class="text-center mb-8">
                <h2 class="text-white text-xl sm:text-2xl font-extrabold mb-1">Portal Tamu</h2>
                <p class="text-emerald-100/60 text-[10px] uppercase font-black tracking-[0.2em]">Silakan masukkan email untuk akses layanan</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-xs text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('tamu.check-email') }}" method="POST" class="space-y-6">
                @csrf
                <div class="group">
                    <label class="block text-emerald-200 text-[10px] uppercase font-black tracking-[0.3em] mb-2 ml-6 opacity-80">Alamat Email</label>
                    <input type="email" name="gmail" class="w-full px-8 py-4 bg-white border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-gray-800 text-sm font-semibold" placeholder="contoh@gmail.com" required>
                </div>
                <button type="submit" class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transition-all mt-8 text-xs tracking-[0.2em] uppercase">Mulai Layanan</button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-white/40 hover:text-white text-[9px] uppercase font-bold tracking-[0.2em] transition-all">Kembali ke Portal Internal</a>
            </div>
        </div>
    </div>
</body>
</html>