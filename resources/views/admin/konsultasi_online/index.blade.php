@extends('layouts.app') 

@section('title', 'Manajemen Konsultasi Admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto animate__animated animate__fadeIn">
    <!-- Header Section (Identik dengan Screenshot + Integrasi Toggle Status Pemateri) -->
    <div class="bg-white rounded-[3rem] py-8 px-10 shadow-sm border border-emerald-50 mb-8 flex flex-col lg:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                <span class="text-emerald-950">MANAJEMEN</span><span class="text-[#008f5d]">KONSULTASI</span>
            </h1>
            <p class="text-[#008f5d] font-bold text-[10px] md:text-xs uppercase tracking-[0.2em] mt-2">
                KELOLA DAN KONFIRMASI JANJI TEMU DARING TAMU.
            </p>
        </div>
        
        <!-- Aksi Kanan: Toggle Status Anda & Akses Master Data -->
        <div class="flex flex-wrap items-center justify-center lg:justify-end gap-4">
            <!-- FITUR UTAMA: Saklar Toggle Status Online/Offline Mandiri Pemateri -->
            <div class="flex items-center gap-3 bg-slate-50 py-2.5 px-5 rounded-full border border-slate-200 shadow-inner">
                <span class="text-[9px] font-black uppercase text-slate-400 tracking-wider">STATUS ANDA:</span>
                <span id="status-label" class="text-[10px] font-black uppercase {{ auth()->user()->status_konsultasi == 'online' ? 'text-[#008f5d]' : 'text-red-500' }}">
                    {{ auth()->user()->status_konsultasi ?? 'OFFLINE' }}
                </span>
                <button onclick="toggleStatusPemateri()" id="toggle-btn" 
                    class="w-11 h-6 rounded-full transition-all duration-300 relative {{ auth()->user()->status_konsultasi == 'online' ? 'bg-[#008f5d]' : 'bg-slate-300' }}">
                    <div id="toggle-circle" class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 {{ auth()->user()->status_konsultasi == 'online' ? 'left-6' : 'left-1' }}"></div>
                </button>
            </div>

            <!-- Master Data Actions -->
            <div class="flex gap-2">
                <button onclick="bukaModal('layanan')" class="bg-white text-emerald-950 border-2 border-emerald-100 font-black py-3 px-5 rounded-full text-[10px] uppercase hover:bg-emerald-50 hover:border-emerald-200 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-list-ul"></i> MASTER LAYANAN
                </button>
                <button onclick="bukaModal('pemateri')" class="bg-[#008f5d] text-white border-2 border-[#008f5d] font-black py-3 px-5 rounded-full text-[10px] uppercase hover:bg-emerald-700 hover:border-emerald-700 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-users"></i> MASTER PEMATERI
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left min-w-[1000px]">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-widest font-black">
                        <th class="py-5 px-6">TAMU</th>
                        <th class="py-5 px-6">DITUJUKAN KEPADA</th>
                        <th class="py-5 px-6">TOPIK</th>
                        <th class="py-5 px-6">WAKTU</th>
                        <th class="py-5 px-6 text-center">STATUS</th>
                        <th class="py-5 px-6 text-center">KETERANGAN</th>
                        <th class="py-5 px-6 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($konsultasi as $item)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="p-6">
                            <span class="block text-emerald-950 font-black text-sm">
                                {{ ($item->kunjungan && $item->kunjungan->tamu) ? $item->kunjungan->tamu->nama_tamu : (\App\Models\Tamu::where('gmail', $item->gmail)->value('nama_tamu') ?? 'Tamu SOWAN') }}
                            </span>
                            <span class="text-[10px] text-[#008f5d] font-bold uppercase">{{ $item->gmail }}</span>
                        </td>
                        <td class="p-6 text-slate-700 font-bold">
                            {{ $item->user->nama_lengkap ?? 'Tidak Diketahui' }}
                        </td>
                        <td class="p-6 text-slate-700 font-medium">{{ $item->topik_konsultasi }}</td>
                        <td class="p-6 text-emerald-950 font-bold">
                            {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('d M Y, H:i') }}
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                {{ $item->status == 'selesai' ? 'bg-emerald-100 text-[#008f5d]' : ($item->status == 'dikonfirmasi' ? 'bg-blue-100 text-blue-600' : ($item->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600')) }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td class="p-6 text-center text-xs italic text-slate-400">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center gap-1">
                                @if($item->status == 'pending')
                                    <button onclick="bukaModalProses({{ $item->id_konsultasi }}, 'konfirmasi')" class="bg-emerald-600 text-white w-8 h-8 rounded-lg font-black text-[10px] shadow-sm hover:bg-emerald-700 transition-all"><i class="fas fa-check"></i></button>
                                    <button onclick="bukaModalProses({{ $item->id_konsultasi }}, 'tolak')" class="bg-red-500 text-white w-8 h-8 rounded-lg font-black text-[10px] shadow-sm hover:bg-red-600 transition-all"><i class="fas fa-times"></i></button>
                                @elseif($item->status == 'dikonfirmasi')
                                    <a href="{{ $item->link_google_meet }}" target="_blank" class="bg-blue-600 text-white w-8 h-8 rounded-lg font-black text-[10px] shadow-sm hover:bg-blue-700 transition-all flex items-center justify-center"><i class="fas fa-video"></i></a>
                                    <form action="{{ route('admin.konsultasi.selesaikan', $item->id_konsultasi) }}" method="POST">
                                        @csrf <button type="submit" class="bg-[#008f5d] text-white w-8 h-8 rounded-lg font-black text-[10px] shadow-sm hover:bg-emerald-700 transition-all"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                                <button onclick="konfirmasiHapus({{ $item->id_konsultasi }})" class="bg-red-500 text-white w-8 h-8 rounded-lg font-black text-[10px] shadow-sm hover:bg-red-600 transition-all"><i class="fas fa-trash-alt"></i></button>
                                <form id="delete-form-{{ $item->id_konsultasi }}" action="{{ route('admin.konsultasi.destroy', $item->id_konsultasi) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 bg-white">
            {{ $konsultasi->links() }}
        </div>
    </div>
</div>

@php
    $master_layanan = \App\Models\Layanan::all();
    $master_pemateri = \App\Models\User::whereIn('role', ['administrator', 'petugas', 'pimpinan'])->get();
@endphp

<!-- Modal Master Layanan -->
<div id="modal-layanan" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] w-full max-w-4xl mx-4 shadow-2xl max-h-[90vh] flex flex-col">
        <h2 class="text-xl font-black text-emerald-950 uppercase italic tracking-tighter mb-6">
            KELOLA MASTER <span class="text-[#008f5d]">LAYANAN</span>
        </h2>
        <div class="overflow-y-auto custom-scrollbar bg-slate-50 rounded-2xl border border-slate-100 p-2">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-widest font-black">
                        <th class="py-4 px-5 rounded-tl-xl rounded-bl-xl">NAMA LAYANAN</th>
                        <th class="py-4 px-5">DESKRIPSI</th>
                        <th class="py-4 px-5 text-center rounded-tr-xl rounded-br-xl">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($master_layanan as $layanan)
                    <tr class="border-b border-slate-100/50 hover:bg-white transition-colors">
                        <td class="p-4 font-black text-emerald-950">{{ $layanan->nama_layanan }}</td>
                        <td class="p-4 text-slate-500 text-xs font-medium">{{ Str::limit($layanan->deskripsi, 50) ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $layanan->status == 'aktif' ? 'bg-[#e6f4ef] text-[#008f5d]' : 'bg-slate-200 text-slate-500' }}">
                                {{ $layanan->status ?? 'Aktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-8 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada data layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8 flex gap-3">
            <button onclick="tutupModal('layanan')" class="w-1/2 py-4 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Tutup</button>
            <a href="{{ route('admin.master.layanan.index') }}" class="w-1/2 flex items-center justify-center py-4 bg-[#008f5d] text-white hover:bg-emerald-700 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Kelola Penuh</a>
        </div>
    </div>
</div>

<!-- Modal Master Pemateri -->
<div id="modal-pemateri" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] w-full max-w-4xl mx-4 shadow-2xl max-h-[90vh] flex flex-col">
        <h2 class="text-xl font-black text-emerald-950 uppercase italic tracking-tighter mb-6">
            KELOLA MASTER <span class="text-[#008f5d]">PEMATERI</span>
        </h2>
        <div class="overflow-y-auto custom-scrollbar bg-slate-50 rounded-2xl border border-slate-100 p-2">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#008f5d] text-white text-[10px] uppercase tracking-widest font-black">
                        <th class="py-4 px-5 rounded-tl-xl rounded-bl-xl">NAMA PEMATERI</th>
                        <th class="py-4 px-5">ROLE</th>
                        <th class="py-4 px-5 text-center rounded-tr-xl rounded-br-xl">STATUS KONSULTASI</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($master_pemateri as $pemateri)
                    <tr class="border-b border-slate-100/50 hover:bg-white transition-colors">
                        <td class="p-4">
                            <span class="block font-black text-emerald-950">{{ $pemateri->nama_lengkap }}</span>
                            <span class="text-[10px] font-bold text-[#008f5d] uppercase">{{ $pemateri->email }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-[9px] font-black uppercase">
                                {{ $pemateri->role }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $pemateri->status_konsultasi == 'online' ? 'bg-[#e6f4ef] text-[#008f5d]' : 'bg-red-50 text-red-500' }}">
                                {{ $pemateri->status_konsultasi ?? 'Offline' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-8 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada data pemateri.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8 flex gap-3">
            <button onclick="tutupModal('pemateri')" class="w-1/2 py-4 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Tutup</button>
            <a href="{{ route('admin.users.index') }}" class="w-1/2 flex items-center justify-center py-4 bg-[#008f5d] text-white hover:bg-emerald-700 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Kelola Penuh</a>
        </div>
    </div>
</div>

<!-- Modal Aksi Dinamis -->
<div id="modal-aksi" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-[2.5rem] w-full max-w-sm mx-4 shadow-xl">
        <h2 id="modal-title" class="text-xl font-black text-emerald-950 mb-6 uppercase tracking-tighter">Proses Konsultasi</h2>
        <form id="form-aksi" method="POST">
            @csrf
            <input type="hidden" name="aksi" id="input-aksi">
            <div id="div-link" class="hidden mb-6">
                <label class="block text-[10px] font-black uppercase mb-2">Link Google Meet</label>
                <input type="url" name="link_google_meet" class="w-full p-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-sm" placeholder="https://meet.google.com/...">
            </div>
            <div id="div-alasan" class="hidden mb-6">
                <label class="block text-[10px] font-black uppercase mb-2">Keterangan / Alasan</label>
                <textarea name="keterangan" class="w-full p-4 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold text-sm" placeholder="Mohon maaf..."></textarea>
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="tutupModal('aksi')" class="w-1/2 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase">Batal</button>
                <button type="submit" class="w-1/2 py-4 bg-[#008f5d] text-white rounded-2xl font-black text-xs uppercase">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleStatusPemateri() {
        fetch('{{ route("user.toggle-status") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            const btn = document.getElementById('toggle-btn');
            const circle = document.getElementById('toggle-circle');
            const label = document.getElementById('status-label');
            if(data.new_status === 'online') {
                btn.className = 'w-11 h-6 rounded-full transition-all duration-300 relative bg-[#008f5d]';
                circle.className = 'absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 left-6';
                label.innerText = 'ONLINE'; label.className = 'text-[10px] font-black uppercase text-[#008f5d]';
            } else {
                btn.className = 'w-11 h-6 rounded-full transition-all duration-300 relative bg-slate-300';
                circle.className = 'absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 left-1';
                label.innerText = 'OFFLINE'; label.className = 'text-[10px] font-black uppercase text-red-500';
            }
        });
    }

    function bukaModalProses(id, aksi) {
        const form = document.getElementById('form-aksi');
        form.action = `/admin/konsultasi/${id}/proses`;
        document.getElementById('input-aksi').value = aksi;
        document.getElementById('div-link').classList.toggle('hidden', aksi !== 'konfirmasi');
        document.getElementById('div-alasan').classList.toggle('hidden', aksi !== 'tolak');
        document.getElementById('modal-title').innerText = aksi === 'konfirmasi' ? 'Konfirmasi Janji' : 'Tolak Janji';
        document.getElementById('modal-aksi').classList.remove('hidden');
    }
    function bukaModal(id) { document.getElementById('modal-' + id).classList.remove('hidden'); }
    function tutupModal(id) { document.getElementById('modal-' + id).classList.add('hidden'); }
    
    function konfirmasiHapus(id) {
        Swal.fire({ 
            title: 'Hapus Sesi Konsultasi?', text: "Data ini akan dihapus permanen dari sistem!", icon: 'warning', 
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!' 
        }).then((res) => { if(res.isConfirmed) document.getElementById('delete-form-'+id).submit(); });
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
    @endif
</script>
@endsection