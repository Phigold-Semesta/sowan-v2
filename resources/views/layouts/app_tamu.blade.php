<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    },
    openKunjungan: false
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SOWAN LPSE Karawang</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { 'sowan-emerald': '#008f5d', 'sowan-gold': '#b45309' } } }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s ease; }
        .sidebar-active { background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        #main-sidebar { width: 88px; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }
            #main-sidebar .nav-text, #main-sidebar .logo-full, #main-sidebar .menu-header { opacity: 0; display: none; transition: opacity 0.3s ease; }
            #main-sidebar:hover .nav-text, #main-sidebar:hover .logo-full, #main-sidebar:hover .menu-header { opacity: 1; display: flex; }
            #main-sidebar .icon-buku-collapsed { display: flex; }
            #main-sidebar:hover .icon-buku-collapsed { display: none; }
            #main-sidebar .nav-item { justify-content: center; }
            #main-sidebar:hover .nav-item { justify-content: flex-start; }
        }
        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; }
            #main-sidebar.show-sidebar { left: 0; }
        }
        .swal2-popup { border-radius: 2rem !important; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f0f9f4] dark:bg-emerald-950 dark:text-emerald-50 transition-colors duration-300">

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden backdrop-blur-sm"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        <aside id="main-sidebar" class="bg-[#008f5d] dark:bg-emerald-900 h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            <div class="p-6 h-24 flex items-center border-b border-white/10 shrink-0">
                <div class="logo-full items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0"><i class="fas fa-book-open text-[#008f5d]"></i></div>
                    <span class="font-extrabold text-lg uppercase leading-none">SOWAN<br><span class="text-emerald-300 text-[10px]">Portal Tamu</span></span>
                </div>
                <div class="icon-buku-collapsed w-full justify-center">
                    <div class="bg-white p-3 rounded-2xl shadow-xl"><i class="fas fa-book-open text-[#008f5d] text-xl"></i></div>
                </div>
            </div>

            <nav class="flex-1 px-4 mt-6 overflow-y-auto custom-scrollbar space-y-2">
                <div class="menu-header px-4 py-2 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Menu Utama</div>
                
                <a href="#" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all hover:bg-white/10">
                    <i class="fas fa-chart-line w-6 text-center text-sm"></i><span class="nav-text ml-3 text-sm font-bold tracking-wide">Dashboard</span>
                </a>

                <div>
                    <button @click="openKunjungan = !openKunjungan" class="w-full nav-item flex items-center py-4 px-5 rounded-2xl transition-all hover:bg-white/10">
                        <i class="fas fa-clipboard-list w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide flex-1 text-left">Kunjungan</span>
                        <i class="nav-text fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="openKunjungan" x-cloak class="pl-5 mt-1 space-y-1">
                        <a href="#" class="flex items-center py-3 px-4 rounded-xl text-sm text-emerald-100 hover:text-white hover:bg-white/10 font-bold transition-all">
                            <i class="fas fa-user-plus w-6 text-xs opacity-70"></i><span class="ml-2">Tamu Baru</span>
                        </a>
                        <a href="#" class="flex items-center py-3 px-4 rounded-xl text-sm text-emerald-100 hover:text-white hover:bg-white/10 font-bold transition-all">
                            <i class="fas fa-user-clock w-6 text-xs opacity-70"></i><span class="ml-2">Tamu Lama</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('tamu.konsultasi_online.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all hover:bg-white/10">
                    <i class="fas fa-video w-6 text-center text-sm"></i><span class="nav-text ml-3 text-sm font-bold tracking-wide">Konsultasi Online</span>
                </a>

                <a href="#" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all hover:bg-white/10">
                    <i class="fas fa-history w-6 text-center text-sm"></i><span class="nav-text ml-3 text-sm font-bold tracking-wide">Riwayat Kunjungan</span>
                </a>
            </nav>

            <div class="p-4 mb-4">
                {{-- Form Logout menggunakan rute custom tamu.logout.tamu --}}
                <form id="logout-form" action="{{ route('tamu.logout.tamu') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <button type="button" onclick="confirmLogout()" class="w-full flex items-center justify-center py-4 rounded-2xl bg-gradient-to-br from-emerald-800 to-emerald-600 hover:shadow-lg transition-all">
                    <i class="fas fa-power-off"></i><span class="nav-text ml-3 text-sm font-bold">LOG OUT</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <header class="h-20 bg-white dark:bg-emerald-900 border-b border-emerald-50 dark:border-emerald-800 shadow-sm flex justify-between items-center px-8">
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 bg-emerald-50 rounded-xl"><i class="fas fa-bars text-emerald-600"></i></button>
                <h2 class="text-slate-800 dark:text-white font-black text-xl uppercase italic">@yield('title')</h2>
                <button @click="toggleTheme()" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-emerald-800 text-yellow-500"><i x-show="darkMode" class="fa-solid fa-sun"></i><i x-show="!darkMode" class="fa-solid fa-moon"></i></button>
            </header>
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">@yield('content')</div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileSidebar() { const sidebar = document.getElementById('main-sidebar'); sidebar.classList.toggle('show-sidebar'); document.getElementById('sidebar-overlay').classList.toggle('hidden'); }
        
        function confirmLogout() { 
            Swal.fire({ 
                title: 'Keluar dari SOWAN?', 
                text: "Anda akan diarahkan kembali ke halaman utama.",
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#008f5d', 
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, LOG OUT' 
            }).then((result) => { 
                if(result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            }); 
        }
    </script>
</body>
</html>