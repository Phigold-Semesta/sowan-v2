<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan SOWAN V2</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #064e3b; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #064e3b; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #064e3b; color: white; padding: 10px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 20px; text-align: right; font-style: italic; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KUNJUNGAN - SOWAN V2</h2>
        <p>LPSE Kabupaten Karawang</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu Masuk</th>
                <th>Nama Tamu</th>
                <th>Instansi</th>
                <th>Layanan</th>
                <th>Tujuan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->waktu_masuk }}</td>
                <td>{{ $row->tamu->nama_tamu ?? '-' }}</td>
                <td>{{ $row->tamu->instansi ?? ($row->tamu->nama_instansi ?? '-') }}</td>
                <td>{{ $row->layanan->nama_layanan ?? '-' }}</td>
                <td>{{ $row->petugas->nama_petugas ?? '-' }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem SOWAN V2.
    </div>
</body>
</html>