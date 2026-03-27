<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem LPSE') | SOWAN v2</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f0f9f4; 
        }
        
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* --- LOGIKA AUTO-HOVER SIDEBAR --- */
        #main-sidebar {
            width: 88px; 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 1024px) {
            #main-sidebar:hover {
                width: 288px; 
            }

            #main-sidebar .nav-text, 
            #main-sidebar .logo-full, 
            #main-sidebar .menu-header {
                opacity: 0;
                transition: opacity 0.3s ease;
                display: none;
            }

            #main-sidebar:hover .nav-text, 
            #main-sidebar:hover .logo-full, 
            #main-sidebar:hover .menu-header {
                opacity: 1;
                display: flex;
            }

            #main-sidebar .icon-buku-collapsed { display: flex; }
            #main-sidebar:hover .icon-buku-collapsed { display: none; }
            
            #main-sidebar .nav-item {
                justify-content: center;
            }
            #main-sidebar:hover .nav-item {
                justify-content: flex-start;
            }

            /* Khusus tombol logout agar selalu center saat di-hover */
            #main-sidebar:hover .btn-logout {
                justify-content: center !important;
            }
        }

        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; }
            #main-sidebar.show-sidebar { left: 0; }
            #main-sidebar .nav-text, #main-sidebar .logo-full { display: flex; opacity: 1; }
            #main-sidebar .icon-buku-collapsed { display: none; }
            .btn-logout { justify-content: center !important; }
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden backdrop-blur-sm"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        
        <aside id="main-sidebar" class="bg-[#008f5d] h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            
            <div class="p-6 h-24 flex items-center border-b border-white/10 shrink-0">
                <div class="logo-full items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0">
                        <i class="fas fa-book-open text-[#008f5d] text-lg"></i>
                    </div>
                    <span class="font-extrabold tracking-tighter text-lg uppercase leading-none">LPSE<br><span class="text-emerald-300 text-xs">Karawang</span></span>
                </div>

                <div class="icon-buku-collapsed w-full justify-center">
                    <div class="bg-white p-3 rounded-2xl shadow-xl border-4 border-emerald-400/30">
                        <i class="fas fa-book-open text-[#008f5d] text-2xl"></i>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 mt-6 overflow-y-auto custom-scrollbar space-y-2">
                <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Menu</div>
                
                <a href="#" class="nav-item flex items-center py-4 px-5 rounded-2xl sidebar-active transition-all">
                    <i class="fas fa-th-large w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Dashboard</span>
                </a>

                <a href="#" class="nav-item flex items-center py-4 px-5 rounded-2xl hover:bg-white/10 transition-all">
                    <i class="fas fa-users w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Data Tamu</span>
                </a>

                <a href="#" class="nav-item flex items-center py-4 px-5 rounded-2xl hover:bg-white/10 transition-all">
                    <i class="fas fa-file-invoice w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Laporan</span>
                </a>
            </nav>

            <div class="p-4 mb-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                    class="btn-logout nav-item w-full flex items-center justify-center py-4 px-6 rounded-2xl bg-white/5 hover:bg-red-500 hover:shadow-[0_8px_20px_rgba(239,68,68,0.4)] text-red-100 transition-all border border-white/5 group">
                    <i class="fas fa-power-off w-6 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-widest uppercase text-nowrap">Logout</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <header class="h-20 bg-white border-b border-emerald-50 shadow-sm flex justify-between items-center px-8 z-20 shrink-0">
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 text-[#008f5d]">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex flex-col leading-none">
                        <h2 class="text-slate-800 font-black text-xl tracking-tighter uppercase italic">@yield('title', 'Dashboard')</h2>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.25em] mt-1.5 hidden sm:block">SOWAN v2 • LPSE Karawang</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-slate-50 py-1.5 pl-4 pr-1.5 rounded-2xl border border-slate-100">
                    <div class="text-right leading-tight hidden md:block">
                        <p class="text-xs font-black text-slate-800 uppercase tracking-tighter">Admin LPSE</p>
                        <div class="flex items-center justify-end gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                            <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest italic leading-none">Online</p>
                        </div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin+LPSE&background=008f5d&color=fff&bold=true" class="w-10 h-10 rounded-xl border-2 border-white shadow-sm">
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show-sidebar');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>