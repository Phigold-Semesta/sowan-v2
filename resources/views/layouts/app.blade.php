<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SOWAN v2</title>
    
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
            theme: {
                extend: {
                    colors: {
                        'sowan-emerald': '#008f5d',
                        'sowan-gold': '#b45309',
                    }
                }
            }
        }
    </script>

    <style>
        /* INTEGRASI VARIABEL WARNA MEWAH */
        :root {
            --emerald-primary: #008f5d;
            --emerald-light: #ecfdf5;
            --gold-accent: #b45309;
            --gold-light: #fffbeb;
            --gray-soft: #6b7280;
            --gray-bg: #f3f4f6;
            
            /* Dark Mode Variants */
            --dark-emerald: #064e3b;
        }

        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            transition: background-color 0.3s ease;
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

        #main-sidebar {
            width: 88px; 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* PENYEMPURNAAN BADGE STATUS */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 800;
            border: 1px solid;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }

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
            
            #main-sidebar .nav-item { justify-content: center; }
            #main-sidebar:hover .nav-item { justify-content: flex-start; }

            #main-sidebar:hover .btn-logout { justify-content: center !important; }
        }

        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; }
            #main-sidebar.show-sidebar { left: 0; }
            #main-sidebar .nav-text, #main-sidebar .logo-full { display: flex; opacity: 1; }
            #main-sidebar .icon-buku-collapsed { display: none; }
            .btn-logout { justify-content: center !important; }
        }

        .swal2-popup {
            border-radius: 2rem !important;
            padding: 2rem !important;
        }
        .swal2-styled.swal2-confirm {
            background-color: #008f5d !important;
            border-radius: 1rem !important;
            padding: 0.75rem 2rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 0.75rem !important;
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f0f9f4] dark:bg-emerald-950 dark:text-emerald-50 transition-colors duration-300">

    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden backdrop-blur-sm"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        
        <aside id="main-sidebar" class="bg-[#008f5d] dark:bg-emerald-900 h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            
            <div class="p-6 h-24 flex items-center border-b border-white/10 shrink-0">
                <div class="logo-full items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0">
                        <i class="fas fa-book-open text-[#008f5d] text-lg"></i>
                    </div>
                    <span class="font-extrabold tracking-tighter text-lg uppercase leading-none">LPSE<br><span class="text-emerald-300 text-xs text-nowrap">Karawang SOWAN</span></span>
                </div>

                <div class="icon-buku-collapsed w-full justify-center">
                    <div class="bg-white p-3 rounded-2xl shadow-xl border-4 border-emerald-400/30">
                        <i class="fas fa-book-open text-[#008f5d] text-2xl"></i>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 mt-6 overflow-y-auto custom-scrollbar space-y-2">
                <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Navigasi Utama</div>
                
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is('dashboard') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">
                        {{ auth()->user()->role === 'pimpinan' ? 'Dashboard Eksekutif' : 'Dashboard' }}
                    </span>
                </a>

                @if(auth()->user()->role === 'petugas')
                <a href="{{ route('petugas.manajemen_tamu.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is('petugas.manajemen_tamu.*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-user-check w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Data Tamu</span>
                </a>
                @endif

                @if(auth()->user()->role === 'administrator')
                <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Manajemen Sistem</div>
                
                <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is('admin.users.*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-users-gear w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Manajemen User</span>
                </a>

                <a href="{{ route('admin.master.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is('admin.master.*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-database w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Master Data</span>
                </a>

                <a href="{{ route('admin.aktivitas.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is('admin.aktivitas.*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-fingerprint w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Aktivitas Global</span>
                </a>
                @endif

                <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Monitoring & Output</div>

                @if(auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas')
                @php
                    $ratingRoute = (auth()->user()->role === 'administrator') ? 'admin.rating.index' : 'petugas.rating.index';
                @endphp
                <a href="{{ route($ratingRoute) }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is($ratingRoute) ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <div class="relative">
                        <i class="fas fa-star-half-stroke w-6 text-center text-sm"></i>
                        @php
                            $pendingRatingCount = \App\Models\RatingLayanan::whereNull('tanggapan')->count();
                        @endphp
                        @if($pendingRatingCount > 0)
                            <span class="absolute -top-2 -right-2 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 text-[9px] items-center justify-center font-bold">{{ $pendingRatingCount }}</span>
                            </span>
                        @endif
                    </div>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Rating Layanan</span>
                </a>
                @endif

                @php
                    $laporanRoute = null;
                    if(auth()->user()->role === 'administrator') {
                        $laporanRoute = 'admin.laporan.index';
                    } elseif(auth()->user()->role === 'pimpinan') {
                        $laporanRoute = 'pimpinan.laporan.index';
                    } elseif(auth()->user()->role === 'petugas') {
                        $laporanRoute = 'petugas.laporan.index';
                    }
                @endphp

                @if($laporanRoute)
                <a href="{{ route($laporanRoute) }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Route::is($laporanRoute) ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-file-export w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">
                        @if(auth()->user()->role === 'pimpinan')
                            Rekapitulasi Laporan
                        @elseif(auth()->user()->role === 'petugas')
                            Laporan Kunjungan
                        @else
                            Laporan Global
                        @endif
                    </span>
                </a>
                @endif
            </nav>

            <div class="p-4 mb-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button onclick="confirmLogout(event)" 
                    class="btn-logout nav-item w-full flex items-center justify-center py-4 px-6 rounded-2xl bg-white/5 hover:bg-red-500 hover:shadow-[0_8px_20px_rgba(239,68,68,0.4)] text-red-100 transition-all border border-white/5 group">
                    <i class="fas fa-power-off w-6 text-center group-hover:scale-110 transition-transform"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-widest uppercase text-nowrap">Logout</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <header class="h-20 bg-white dark:bg-emerald-900 border-b border-emerald-50 dark:border-emerald-800 shadow-sm flex justify-between items-center px-8 z-20 shrink-0 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-800 text-[#008f5d] dark:text-emerald-400">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex flex-col leading-none">
                        <h2 class="text-slate-800 dark:text-white font-black text-xl tracking-tighter uppercase italic">@yield('title', 'Dashboard')</h2>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-emerald-400/60 uppercase tracking-[0.25em] mt-1.5 hidden sm:block">SOWAN v2 • LPSE Karawang</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="toggleTheme()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-emerald-800 text-slate-500 dark:text-yellow-400 border border-slate-100 dark:border-emerald-700 transition-all hover:scale-110">
                        <i x-show="darkMode" class="fa-solid fa-sun text-lg" x-cloak></i>
                        <i x-show="!darkMode" class="fa-solid fa-moon text-lg" x-cloak></i>
                    </button>

                    <div class="flex items-center gap-4 bg-slate-50 dark:bg-emerald-800/50 py-1.5 pl-4 pr-1.5 rounded-2xl border border-slate-100 dark:border-emerald-700">
                        <div class="text-right leading-tight hidden md:block">
                            <p class="text-xs font-black text-slate-800 dark:text-emerald-50 uppercase tracking-tighter">{{ auth()->user()->nama_lengkap }}</p>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                                <p class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest italic leading-none">{{ strtoupper(auth()->user()->role) }}</p>
                            </div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_lengkap) }}&background=008f5d&color=fff&bold=true" class="w-10 h-10 rounded-xl border-2 border-white dark:border-emerald-700 shadow-sm">
                    </div>
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

        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Sesi Anda akan segera diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008f5d',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, LOGOUT',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
                customClass: {
                    cancelButton: 'text-slate-600 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Waduh...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#008f5d',
                    background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
                });
            @endif
        });
    </script>
</body>
</html>