@extends('layouts.app_tamu')

@section('title', 'Konsultasi Online')

@section('content')
<div class="max-w-7xl mx-auto animate__animated animate__fadeIn">
    <!-- Header Section -->
    <div class="bg-white dark:bg-emerald-900 rounded-[2.5rem] p-8 shadow-sm border border-emerald-100 dark:border-emerald-800 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-emerald-950 dark:text-white uppercase italic tracking-tighter">
                Konsultasi <span class="text-[#008f5d] dark:text-emerald-400">Online</span>
            </h1>
            <p class="text-emerald-600 dark:text-emerald-300 mt-2 font-bold text-xs md:text-sm uppercase tracking-widest">
                Kelola jadwal pertemuan daring Anda dengan profesional.
            </p>
        </div>
        <button onclick="toggleModal('modal-konsultasi')" class="bg-[#008f5d] hover:bg-emerald-700 text-white font-black py-4 px-8 rounded-2xl shadow-lg shadow-emerald-900/10 transition-all flex items-center gap-3 active:scale-95 uppercase tracking-widest text-xs">
            <i class="fas fa-plus-circle text-lg"></i> Buat Janji Baru
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-[0.2em] font-black">
                        <th class="p-6">Topik Konsultasi</th>
                        <th class="p-6">Petugas Pemateri</th>
                        <th class="p-6">Waktu Pelaksanaan</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Keterangan</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-slate-700 dark:text-slate-200">
                    @forelse($jadwal_konsultasi as $item)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-emerald-50/30 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-6 text-emerald-900 dark:text-white text-base">{{ $item->topik_konsultasi }}</td>
                        <td class="p-6 text-emerald-700 dark:text-emerald-300">{{ $item->user->nama_lengkap ?? 'Petugas LPSE' }}</td>
                        <td class="p-6">
                            <span class="block text-emerald-900 dark:text-white">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y') }}</span>
                            <span class="text-[10px] text-emerald-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} WIB</span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-4 py-2 rounded-full text-[10px] uppercase tracking-widest font-black shadow-sm border
                                {{ $item->status == 'dikonfirmasi' ? 'bg-emerald-100 text-[#008f5d] border-emerald-200' : 
                                   ($item->status == 'ditolak' ? 'bg-red-100 text-red-600 border-red-200' : 'bg-slate-100 text-slate-600 border-slate-200') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <!-- Kolom Catatan/Keterangan Baru -->
                        <td class="p-6 text-center">
                            <div class="text-[11px] text-slate-500 italic max-w-[200px] mx-auto">
                                {{ $item->alasan_penolakan ?? '-' }}
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            @if($item->status == 'dikonfirmasi' && $item->link_google_meet)
                                <a href="{{ $item->link_google_meet }}" target="_blank" class="inline-block bg-emerald-600 text-white py-2 px-6 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md">
                                    <i class="fas fa-video mr-1"></i> Gabung
                                </a>
                            @else
                                <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-xl bg-slate-50 text-slate-400 border border-slate-200">
                                    <i class="fas fa-clock mr-1"></i> {{ $item->status == 'ditolak' ? 'Selesai' : 'Menunggu' }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400">
                            <i class="fas fa-video-slash text-4xl mb-4 opacity-50"></i>
                            <p class="text-xs font-black uppercase tracking-widest">Belum ada jadwal konsultasi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Buat Janji -->
<div id="modal-konsultasi" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] w-full max-w-lg shadow-xl relative mx-4 border border-slate-100 dark:border-slate-700">
        <h2 class="text-2xl font-black text-emerald-950 dark:text-white mb-6 uppercase tracking-tighter italic">Buat Janji <span class="text-[#008f5d]">Konsultasi</span></h2>
        <form action="{{ route('tamu.konsultasi.simpan') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-2 ml-2">Topik Konsultasi</label>
                    <input type="text" name="topik_konsultasi" class="w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 focus:border-emerald-500 font-bold text-sm text-slate-700 dark:text-slate-200 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-2 ml-2">Layanan</label>
                    <select name="id_layanan" class="w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 focus:border-emerald-500 font-bold text-sm text-slate-700 dark:text-slate-200 outline-none transition-all" required>
                        <option value="" disabled selected>Pilih Layanan LPSE</option>
                        @foreach($layanan as $l) <option value="{{ $l->id_layanan }}">{{ $l->nama_layanan }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-2 ml-2">Petugas Pemateri</label>
                    <select name="id_petugas" class="w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 focus:border-emerald-500 font-bold text-sm text-slate-700 dark:text-slate-200 outline-none transition-all" required>
                        <option value="" disabled selected>Pilih Pemateri</option>
                        @foreach($petugas as $p) <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }} ({{ ucfirst($p->role) }})</option> @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-2 ml-2">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 focus:border-emerald-500 font-bold text-sm text-slate-700 dark:text-slate-200 outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 dark:text-emerald-400 uppercase tracking-widest mb-2 ml-2">Durasi (Menit)</label>
                        <input type="number" name="durasi_menit" value="30" min="15" max="90" step="15" class="w-full p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 focus:border-emerald-500 font-bold text-sm text-slate-700 dark:text-slate-200 outline-none transition-all" required>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex gap-4">
                <button type="button" onclick="toggleModal('modal-konsultasi')" class="w-1/3 py-4 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="w-2/3 py-4 bg-[#008f5d] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg">Simpan Janji</button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
</script>
@endsection