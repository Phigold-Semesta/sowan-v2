@extends('layouts.app')

@section('title', 'Monitoring Antrean & Data Tamu')

@section('content')
<div class="space-y-8 animate__animated animate__fadeIn">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic leading-none">
                Monitoring <span class="text-[#008f5d] dark:text-emerald-400">Antrean Tamu</span>
            </h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="h-1.5 w-12 bg-gradient-to-r from-[#008f5d] to-emerald-300 rounded-full"></span>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">
                    Sistem Operasional Digital LPSE Karawang
                </p>
            </div>
        </div>
        <a href="{{ route('petugas.manajemen_tamu.create') }}" 
           class="inline-flex items-center px-8 py-4 bg-[#008f5d] dark:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-[2rem] hover:bg-emerald-700 dark:hover:bg-emerald-500 hover:shadow-[0_15px_30px_rgba(0,143,93,0.3)] transition-all duration-500 group shrink-0 border-b-4 border-[#006b46] dark:border-emerald-800 active:border-b-0 active:translate-y-1">
            <i class="fas fa-user-plus mr-3 group-hover:rotate-12 transition-transform"></i>
            Registrasi Manual
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $stats = [
                ['label' => 'Menunggu', 'count' => $kunjungans->where('status', 'belum dilayani')->count(), 'color' => 'amber', 'icon' => 'fa-clock-rotate-left', 'shadow' => 'shadow-amber-500/10'],
                ['label' => 'Diproses', 'count' => $kunjungans->where('status', 'sedang dilayani')->count(), 'color' => 'blue', 'icon' => 'fa-bolt-lightning', 'shadow' => 'shadow-blue-500/10'],
                ['label' => 'Selesai', 'count' => $kunjungans->where('status', 'sudah dilayani')->count(), 'color' => 'emerald', 'icon' => 'fa-check-double', 'shadow' => 'shadow-emerald-500/10']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="group relative bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden transition-all duration-500 hover:-translate-y-2 {{ $stat['shadow'] }}">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $stat['color'] }}-500/5 rounded-full blur-3xl group-hover:bg-{{ $stat['color'] }}-500/20 transition-all"></div>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 flex items-center justify-center text-{{ $stat['color'] }}-500 text-xl shadow-inner border border-{{ $stat['color'] }}-100 dark:border-{{ $stat['color'] }}-800 transition-transform group-hover:scale-110">
                    <i class="fas {{ $stat['icon'] }} @if($stat['label'] == 'Diproses') fa-beat-fast @endif"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-1">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-black text-slate-800 dark:text-white tabular-nums">{{ $stat['count'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white dark:bg-slate-800 p-5 rounded-[2.5rem] border border-emerald-50 dark:border-slate-700 shadow-sm transition-colors duration-300">
        <form action="{{ URL::current() }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            {{-- Search Bar --}}
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#008f5d] dark:group-focus-within:text-emerald-400 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="CARI NAMA TAMU ATAU INSTANSI..." 
                       class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[11px] font-bold tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:focus:ring-emerald-500/20 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 uppercase">
            </div>

            <div class="flex flex-wrap md:flex-nowrap gap-3">
                <div class="w-full md:w-40 relative">
                    <select name="per_page" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer text-center">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page', 10) == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-list-ol text-[10px]"></i>
                    </div>
                </div>

                <div class="w-full md:w-44 relative">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-10 pr-10 py-3.5 bg-slate-50 dark:bg-slate-900/50 border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#008f5d]/20 dark:text-slate-200 appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="belum dilayani" {{ request('status') == 'belum dilayani' ? 'selected' : '' }}>Pending</option>
                        <option value="sedang dilayani" {{ request('status') == 'sedang dilayani' ? 'selected' : '' }}>Diproses</option>
                        <option value="sudah dilayani" {{ request('status') == 'sudah dilayani' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-filter text-xs"></i>
                    </div>
                </div>

                <button type="submit" class="px-6 py-3.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f5d] dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#008f5d] hover:text-white dark:hover:bg-emerald-600 transition-all duration-300 shadow-sm active:scale-95">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-10 py-7 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-7 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Layanan & Petugas</th>
                        <th class="px-6 py-7 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="px-10 py-7 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    @forelse($kunjungans as $item)
                    <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-950/10 transition-all duration-300 group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-5">
                                <div class="relative shrink-0">
                                    <div class="w-14 h-14 rounded-[1.5rem] bg-gradient-to-br from-[#008f5d] to-emerald-300 p-0.5 shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                        <div class="w-full h-full rounded-[1.4rem] bg-white dark:bg-slate-800 flex items-center justify-center">
                                            <span class="text-transparent bg-clip-text bg-gradient-to-br from-[#008f5d] to-emerald-400 font-black text-xl">
                                                {{ substr($item->tamu->nama_tamu ?? '?', 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate mb-0.5 group-hover:text-[#008f5d] transition-colors">
                                        {{ $item->tamu->nama_tamu ?? 'Tamu Tidak Terdaftar' }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                        <i class="fas fa-building text-[8px] mr-1"></i> {{ $item->tamu->nama_instansi ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col gap-1.5">
                                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase rounded-xl border border-emerald-100 dark:border-emerald-800/30 w-fit">
                                    <i class="fas fa-concierge-bell mr-1"></i> {{ $item->layanan->nama_layanan ?? 'Layanan Tidak Terdaftar' }}
                                </span>
                                <p class="text-[10px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-tighter">
                                    <i class="fas fa-user-tie mr-1 text-[#008f5d]"></i> Menemui: {{ $item->petugasTujuan->nama_petugas ?? 'Petugas Piket' }}
                                </p>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter italic">
                                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($item->waktu_masuk)->diffForHumans() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $statusMap = [
                                    'belum dilayani' => [
                                        'bg' => 'bg-amber-50 dark:bg-amber-900/20',
                                        'text' => 'text-amber-600 dark:text-amber-400',
                                        'border' => 'border-amber-100 dark:border-amber-900/30',
                                        'icon' => 'fa-hourglass-start',
                                        'label' => 'BELUM DILAYANI'
                                    ],
                                    'sedang dilayani' => [
                                        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                        'text' => 'text-blue-600 dark:text-blue-400',
                                        'border' => 'border-blue-100 dark:border-blue-900/30',
                                        'icon' => 'fa-bolt',
                                        'label' => 'SEDANG DILAYANI'
                                    ],
                                    'sudah dilayani' => [
                                        'bg' => 'bg-emerald-50 dark:bg-emerald-900/20',
                                        'text' => 'text-emerald-600 dark:text-emerald-400',
                                        'border' => 'border-emerald-100 dark:border-emerald-900/30',
                                        'icon' => 'fa-check-circle',
                                        'label' => 'SUDAH DILAYANI'
                                    ],
                                ];
                                $st = $statusMap[$item->status] ?? [
                                    'bg' => 'bg-slate-50 dark:bg-slate-900/20',
                                    'text' => 'text-slate-600 dark:text-slate-400',
                                    'border' => 'border-slate-100 dark:border-slate-900/30',
                                    'icon' => 'fa-circle',
                                    'label' => strtoupper($item->status)
                                ];
                            @endphp
                            <span class="inline-flex items-center text-[9px] font-black uppercase tracking-[0.2em] px-5 py-2.5 rounded-2xl border-2 {{ $st['border'] }} {{ $st['bg'] }} {{ $st['text'] }} shadow-sm transition-all duration-500">
                                <i class="fas {{ $st['icon'] }} mr-2 @if($item->status == 'sedang dilayani') fa-spin-pulse @endif"></i>
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex justify-end items-center gap-3">
                                <button type="button" onclick="updateStatus('{{ $item->id_kunjungan }}', '{{ addslashes($item->tamu->nama_tamu ?? 'Tamu') }}', '{{ $item->status }}')"
                                        class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-emerald-50 dark:bg-emerald-900/20 text-[#008f5d] dark:text-emerald-400 hover:bg-[#008f5d] hover:text-white transition-all shadow-sm border border-emerald-100 dark:border-emerald-800/50 group/btn">
                                    <i class="fas fa-pen-to-square text-sm"></i>
                                </button>
                                <a href="{{ route('petugas.manajemen_tamu.show', $item->id_kunjungan) }}" 
                                   class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 dark:border-blue-800/50">
                                    <i class="fas fa-expand text-sm"></i>
                                </a>
                                <button type="button" onclick="confirmDelete('{{ $item->id_kunjungan }}', '{{ addslashes($item->tamu->nama_tamu ?? 'Tamu') }}')"
                                        class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100 dark:border-red-800/50">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                                
                                <form id="status-form-{{ $item->id_kunjungan }}" action="{{ route('petugas.manajemen_tamu.updateStatus', $item->id_kunjungan) }}" method="POST" class="hidden">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" id="input-status-{{ $item->id_kunjungan }}">
                                </form>
                                <form id="delete-form-{{ $item->id_kunjungan }}" action="{{ route('petugas.manajemen_tamu.destroy', $item->id_kunjungan) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-[2.5rem] flex items-center justify-center mb-6 text-slate-200 dark:text-slate-800 border border-slate-100 dark:border-slate-700">
                                    <i class="fas fa-search fa-3x"></i>
                                </div>
                                <h3 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Antrean Tidak Ditemukan, Bos!</h3>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($kunjungans, 'hasPages') && ($kunjungans->hasPages() || $kunjungans->total() > 0))
        <div class="px-10 py-8 bg-slate-50/50 dark:bg-slate-900/30 border-t border-emerald-50 dark:border-slate-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-[#008f5d] animate-pulse"></div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">
                        Tampil {{ $kunjungans->firstItem() ?? 0 }} - {{ $kunjungans->lastItem() ?? 0 }} dari {{ $kunjungans->total() ?? 0 }} Antrean
                    </p>
                </div>
                <div class="custom-pagination">
                    {{ $kunjungans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #008f5d33; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #008f5d; }

    /* Pagination Modern Styling */
    .custom-pagination nav > div:first-child { display: none; }
    .custom-pagination nav span[aria-current="page"] > span {
        background: #008f5d !important;
        border-radius: 14px;
        font-weight: 800;
        font-size: 10px;
        color: white !important;
        box-shadow: 0 4px 12px rgba(0,143,93,0.2);
        border: none;
        padding: 8px 16px;
    }
    .custom-pagination nav a, .custom-pagination nav span > span, .custom-pagination nav span > a {
        border-radius: 14px;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        background-color: #ffffff;
        color: #64748b;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .dark .custom-pagination nav a, 
    .dark .custom-pagination nav span > span,
    .dark .custom-pagination nav span > a { 
        background-color: #0f172a; 
        color: #94a3b8; 
        border-color: #1e293b; 
    }
    .custom-pagination nav a:hover { background-color: #008f5d; color: white; border-color: #008f5d; transform: translateY(-2px); }
</style>

<script>
    function updateStatus(id, name, currentStatus) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'UPDATE STATUS PELAYANAN',
            html: `
                <div class="mb-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tamu Sedang Dilayani</p>
                    <p class="text-sm font-bold text-[#008f5d] dark:text-emerald-400 uppercase tracking-tight italic">${name}</p>
                </div>
            `,
            icon: 'info',
            iconColor: '#008f5d',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            showCancelButton: true,
            confirmButtonText: 'SIMPAN PERUBAHAN',
            cancelButtonText: 'BATAL',
            confirmButtonColor: '#008f5d',
            input: 'radio',
            inputOptions: {
                'belum dilayani': '⌛ Menunggu Antrean',
                'sedang dilayani': '⚡ Sedang Dilayani',
                'sudah dilayani': '✅ Selesai Dilayani'
            },
            inputValue: currentStatus,
            customClass: {
                popup: 'rounded-[3rem] border-4 border-emerald-50 dark:border-slate-700 p-8 shadow-2xl',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-4 uppercase shadow-lg shadow-emerald-500/20',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-8 py-4',
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Pilih status terlebih dahulu!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const inputField = document.getElementById(`input-status-${id}`);
                const form = document.getElementById(`status-form-${id}`);
                if (inputField && form) {
                    inputField.value = result.value;
                    Swal.fire({ 
                        title: 'Memproses...', 
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#1e293b',
                        didOpen: () => { Swal.showLoading() }, 
                        showConfirmButton: false 
                    });
                    form.submit();
                }
            }
        });
    }

    function confirmDelete(id, name) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'HAPUS DATA ANTREAN?',
            html: `<div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Anda akan menghapus riwayat kunjungan: <br><b class="text-[#ef4444] text-sm">${name}</b></div>`,
            icon: 'warning',
            iconColor: '#ef4444',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, HAPUS PERMANEN!',
            cancelButtonText: 'BATALKAN',
            customClass: {
                popup: 'rounded-[3rem] border-4 border-red-50 dark:border-slate-700 shadow-2xl',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-4 uppercase',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-8 py-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`delete-form-${id}`);
                if(form) form.submit();
            }
        });
    }
</script>
@endsection