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
           class="inline-flex items-center px-8 py-4 bg-slate-900 dark:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-[2rem] hover:bg-[#008f5d] dark:hover:bg-emerald-500 hover:shadow-[0_15px_30px_rgba(0,143,93,0.3)] transition-all duration-500 group shrink-0 border-b-4 border-slate-700 dark:border-emerald-800 active:border-b-0 active:translate-y-1">
            <i class="fas fa-user-plus mr-3 group-hover:rotate-12 transition-transform"></i>
            Registrasi Manual
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $stats = [
                ['label' => 'Menunggu', 'count' => $kunjungans->where('status', 'belum dilayani')->count(), 'color' => 'red', 'icon' => 'fa-clock-rotate-left'],
                ['label' => 'Diproses', 'count' => $kunjungans->where('status', 'sedang dilayani')->count(), 'color' => 'amber', 'icon' => 'fa-bolt-lightning'],
                ['label' => 'Selesai', 'count' => $kunjungans->where('status', 'sudah dilayani')->count(), 'color' => 'emerald', 'icon' => 'fa-check-double']
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="group relative bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-xl overflow-hidden transition-all duration-500 hover:-translate-y-2">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $stat['color'] }}-500/5 rounded-full blur-3xl group-hover:bg-{{ $stat['color'] }}-500/20 transition-all"></div>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 flex items-center justify-center text-{{ $stat['color'] }}-500 text-xl shadow-inner border border-{{ $stat['color'] }}-100 dark:border-{{ $stat['color'] }}-800">
                    <i class="fas {{ $stat['icon'] }} @if($stat['color'] == 'amber') fa-beat-fast @endif"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-1">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-black text-slate-800 dark:text-white tabular-nums">{{ $stat['count'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Table Section --}}
    <div class="bg-white dark:bg-slate-800 rounded-[3rem] border border-slate-100 dark:border-slate-700 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Tamu</th>
                        <th class="px-6 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Keperluan</th>
                        <th class="px-6 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                        <th class="px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                    @forelse($kunjungans as $item)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40 transition-all duration-300 group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-5">
                                <div class="relative shrink-0">
                                    <div class="w-14 h-14 rounded-[1.5rem] bg-gradient-to-br from-[#008f5d] to-emerald-300 p-0.5 shadow-lg group-hover:scale-110 transition-all duration-500">
                                        <div class="w-full h-full rounded-[1.4rem] bg-white dark:bg-slate-800 flex items-center justify-center">
                                            <span class="text-transparent bg-clip-text bg-gradient-to-br from-[#008f5d] to-emerald-400 font-black text-xl">
                                                {{ substr($item->tamu->nama_tamu, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-sm">
                                        <div class="w-3 h-3 rounded-full {{ $item->status == 'sudah dilayani' ? 'bg-emerald-500' : ($item->status == 'sedang dilayani' ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight truncate mb-0.5 group-hover:text-[#008f5d] transition-colors">
                                        {{ $item->tamu->nama_tamu }}
                                    </p>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                        <i class="fas fa-building text-[8px] mr-1"></i> {{ $item->tamu->nama_instansi }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 italic line-clamp-1">"{{ $item->keperluan }}"</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $statusMap = [
                                    'belum dilayani' => ['color' => 'red', 'icon' => 'fa-hourglass-start', 'label' => 'Pending'],
                                    'sedang dilayani' => ['color' => 'amber', 'icon' => 'fa-bolt', 'label' => 'Diproses'],
                                    'sudah dilayani' => ['color' => 'emerald', 'icon' => 'fa-check-circle', 'label' => 'Selesai'],
                                ];
                                $st = $statusMap[$item->status] ?? ['color' => 'slate', 'icon' => 'fa-circle', 'label' => $item->status];
                            @endphp
                            <span class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-2xl border-2 border-{{ $st['color'] }}-100 dark:border-{{ $st['color'] }}-900/30 bg-{{ $st['color'] }}-50 dark:bg-{{ $st['color'] }}-900/20 text-{{ $st['color'] }}-600 dark:text-{{ $st['color'] }}-400">
                                <i class="fas {{ $st['icon'] }} mr-2 opacity-80"></i>
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex justify-end items-center gap-3">
                                {{-- Tombol Update via SweetAlert --}}
                                <button type="button" 
                                        onclick="updateStatus('{{ $item->id_kunjungan }}', '{{ $item->tamu->nama_tamu }}', '{{ $item->status }}')"
                                        class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-emerald-50 dark:bg-emerald-900/20 text-[#008f5d] hover:bg-[#008f5d] hover:text-white transition-all active:scale-90 shadow-sm border border-emerald-100 dark:border-emerald-800/50">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>

                                <a href="{{ route('petugas.manajemen_tamu.show', $item->id_kunjungan) }}" 
                                   class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-600 hover:text-white transition-all active:scale-90 shadow-sm border border-blue-100 dark:border-blue-800/50">
                                    <i class="fas fa-expand text-sm"></i>
                                </a>

                                <button type="button" onclick="confirmDelete('{{ $item->id_kunjungan }}', '{{ $item->tamu->nama_tamu }}')"
                                        class="w-11 h-11 flex items-center justify-center rounded-[1.2rem] bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-600 hover:text-white transition-all active:scale-90 shadow-sm border border-red-100 dark:border-red-800/50">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>

                                <form id="status-form-{{ $item->id_kunjungan }}" action="{{ route('petugas.manajemen_tamu.updateStatus', $item->id_kunjungan) }}" method="POST" class="hidden">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" id="input-status-{{ $item->id_kunjungan }}">
                                </form>

                                <form action="{{ route('petugas.manajemen_tamu.destroy', $item->id_kunjungan) }}" id="delete-form-{{ $item->id_kunjungan }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-32 text-center text-slate-400 uppercase font-black tracking-widest text-xs">Antrean Kosong, Bos!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Styling khusus untuk Radio Button di dalam SweetAlert */
    .swal2-radio-custom {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
        padding: 20px !important;
    }
    .swal2-radio-custom label {
        display: flex !important;
        align-items: center;
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 1.2rem;
        border: 2px solid #f1f5f9;
        font-weight: 800 !important;
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        cursor: pointer;
        transition: all 0.3s;
    }
    .dark .swal2-radio-custom label { background: #0f172a; border-color: #1e293b; color: #cbd5e1; }
    .swal2-radio-custom input[type="radio"] {
        margin-right: 15px;
        width: 18px;
        height: 18px;
        accent-color: #008f5d;
    }
    .swal2-radio-custom label:has(input:checked) {
        border-color: #008f5d;
        background: #f0fdf4;
        color: #008f5d;
    }
    .dark .swal2-radio-custom label:has(input:checked) { background: #064e3b; color: #34d399; }
</style>

<script>
    function updateStatus(id, name, currentStatus) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: 'UPDATE PELAYANAN',
            html: `<div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4">Tentukan status untuk: <br><b class="text-slate-800 dark:text-white text-sm">${name}</b></div>`,
            icon: 'info',
            iconColor: '#008f5d',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            showCancelButton: true,
            confirmButtonText: 'UPDATE STATUS',
            cancelButtonText: 'BATAL',
            confirmButtonColor: '#008f5d',
            cancelButtonColor: isDark ? '#334155' : '#f1f5f9',
            reverseButtons: true,
            input: 'radio',
            inputOptions: {
                'belum dilayani': '⌛ Menunggu Antrean',
                'sedang dilayani': '⚡ Sedang Dilayani',
                'sudah dilayani': '✅ Selesai Dilayani'
            },
            inputValue: currentStatus,
            customClass: {
                popup: 'rounded-[3rem] border-4 border-emerald-50 dark:border-slate-700 p-8',
                title: 'font-black tracking-tighter italic text-2xl',
                input: 'swal2-radio-custom',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-4',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-8 py-4'
            },
            inputValidator: (value) => {
                if (!value) return 'Pilih salah satu status, bos!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`input-status-${id}`).value = result.value;
                document.getElementById(`status-form-${id}`).submit();
            }
        });
    }

    function confirmDelete(id, name) {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            title: 'HAPUS DATA TAMU?',
            html: `<div class="text-sm">Anda akan menghapus riwayat kunjungan <br><b class="text-[#008f5d] uppercase tracking-tighter text-lg">${name}</b>.</div>`,
            icon: 'warning',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#1e293b',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, EKSEKUSI!',
            cancelButtonText: 'TIDAK',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[3rem] border-4 border-red-50 dark:border-slate-700',
                confirmButton: 'rounded-2xl font-black text-[10px] tracking-widest px-8 py-4',
                cancelButton: 'rounded-2xl font-black text-[10px] tracking-widest text-slate-500 px-8 py-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection