@extends('layouts.app') {{-- Sesuaikan dengan layout petugas Anda --}}

@section('title', 'Manajemen Konsultasi')

@section('content')
<div class="max-w-7xl mx-auto animate__animated animate__fadeIn">
    <!-- Header Section -->
    <div class="bg-white dark:bg-emerald-900 rounded-[2.5rem] p-8 shadow-sm border border-emerald-100 dark:border-emerald-800 mb-8">
        <h1 class="text-3xl md:text-4xl font-black text-emerald-950 dark:text-white uppercase italic tracking-tighter">
            Manajemen <span class="text-[#008f5d] dark:text-emerald-400">Konsultasi</span>
        </h1>
        <p class="text-emerald-600 dark:text-emerald-300 mt-2 font-bold text-xs uppercase tracking-widest">
            Kelola dan konfirmasi janji temu daring tamu.
        </p>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-[0.2em] font-black">
                        <th class="p-6">Tamu</th>
                        <th class="p-6">Topik</th>
                        <th class="p-6">Waktu</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-slate-700 dark:text-slate-200">
                    @forelse($konsultasi as $item)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-emerald-50/30 transition-colors">
                        <td class="p-6">
                            {{-- Perbaikan: Kita cek langsung dari email jika relasi kunjungan/tamu mungkin null --}}
                          <span class="block text-emerald-950 dark:text-white">
    {{-- Kita ambil langsung dari relasi kunjungan yang sudah terhubung dengan tabel tamu --}}
    {{ $item->kunjungan && $item->kunjungan->tamuRelasi ? $item->kunjungan->tamuRelasi->nama_tamu : 'Tamu SOWAN' }}
</span>
                            <span class="text-[10px] text-emerald-600 uppercase">
                                {{ $item->gmail ?? 'Email tidak ditemukan' }}
                            </span>
                        </td>
                        <td class="p-6 text-slate-600 dark:text-slate-300">{{ $item->topik_konsultasi }}</td>
                        <td class="p-6">
                            <span class="block text-emerald-900 dark:text-white">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-4 py-2 rounded-full text-[10px] uppercase font-black shadow-sm 
                                {{ $item->status == 'dikonfirmasi' ? 'bg-emerald-100 text-[#008f5d]' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            @if($item->status == 'pending')
                                <button onclick="bukaModalKonfirmasi({{ $item->id_konsultasi }})" class="bg-[#008f5d] text-white py-2 px-6 rounded-xl font-black text-[10px] uppercase hover:bg-emerald-700 transition-all shadow-md">
                                    Konfirmasi
                                </button>
                            @else
                                <span class="text-[10px] text-slate-400 italic">Terkonfirmasi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                            Tidak ada jadwal konsultasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">{{ $konsultasi->links() }}</div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="modal-konfirmasi" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-[2.5rem] w-full max-w-sm mx-4 shadow-xl">
        <h2 class="text-xl font-black text-emerald-950 mb-6 uppercase tracking-tighter">Masukkan Link Meet</h2>
        <form id="form-konfirmasi" method="POST">
            @csrf
            <input type="url" name="link_google_meet" class="w-full p-4 rounded-2xl bg-slate-50 border-2 border-slate-100 mb-6 outline-none focus:border-emerald-500 font-bold text-sm" placeholder="https://meet.google.com/..." required>
            <div class="flex gap-4">
                <button type="button" onclick="tutupModal()" class="w-1/2 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase">Batal</button>
                <button type="submit" class="w-1/2 py-4 bg-[#008f5d] text-white rounded-2xl font-black text-xs uppercase">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalKonfirmasi(id) {
        const form = document.getElementById('form-konfirmasi');
        form.action = `/petugas/konsultasi/${id}/konfirmasi`;
        document.getElementById('modal-konfirmasi').classList.remove('hidden');
    }
    function tutupModal() {
        document.getElementById('modal-konfirmasi').classList.add('hidden');
    }
</script>
@endsection