@extends('layouts.app_tamu')

@section('content')
<div class="p-6">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-emerald-900 dark:text-emerald-50 uppercase tracking-tight">Konsultasi Online</h1>
            <p class="text-emerald-700 dark:text-emerald-300">Kelola jadwal pertemuan daring Anda dengan profesional.</p>
        </div>
        <button onclick="toggleModal('modal-konsultasi')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition-all flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Buat Janji Baru
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-emerald-100 dark:border-emerald-900 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-emerald-800 text-white">
                    <th class="p-4 font-bold">Topik Konsultasi</th>
                    <th class="p-4 font-bold">Petugas</th>
                    <th class="p-4 font-bold">Waktu Mulai</th>
                    <th class="p-4 font-bold">Status</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal_konsultasi as $item)
                <tr class="border-b border-emerald-50 hover:bg-emerald-50/50 transition-colors">
                    <td class="p-4 font-semibold text-emerald-900">{{ $item->topik_konsultasi }}</td>
                    <td class="p-4 text-slate-600">{{ $item->nama_petugas ?? 'Petugas LPSE' }}</td>
                    <td class="p-4 text-slate-600">{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i') }} WIB</td>
                    <td class="p-4"><span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">{{ ucfirst($item->status) }}</span></td>
                    <td class="p-4 text-center">
                        @if($item->link_google_meet)
                            <a href="{{ $item->link_google_meet }}" target="_blank" class="bg-emerald-600 text-white py-2 px-4 rounded-xl font-bold text-sm hover:bg-emerald-700">Gabung Meet</a>
                        @else
                            <span class="text-slate-400 text-sm italic">Menunggu Link</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-10 text-center text-slate-500">Belum ada jadwal konsultasi terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-konsultasi" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-3xl w-full max-w-lg shadow-2xl border-4 border-emerald-500">
        <h2 class="text-2xl font-black text-emerald-900 mb-6">Buat Janji Konsultasi</h2>
        <form action="{{ route('tamu.konsultasi.simpan') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-1">Layanan</label>
                    <select name="id_layanan" class="w-full p-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-emerald-500">
                        @foreach($layanan as $l)
                            <option value="{{ $l->id_layanan }}">{{ $l->nama_layanan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-1">Petugas Pemateri</label>
                    <select name="id_petugas" class="w-full p-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-emerald-500">
                        @foreach($petugas as $p)
                            <option value="{{ $p->id_petugas }}">{{ $p->nama_petugas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-emerald-800 mb-1">Waktu Rencana</label>
                    <input type="datetime-local" name="waktu_mulai" class="w-full p-3 rounded-xl border border-emerald-200" required>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="toggleModal('modal-konsultasi')" class="flex-1 py-3 bg-slate-200 rounded-xl font-bold">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl font-bold">Simpan Janji</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}
</script>
@endsection