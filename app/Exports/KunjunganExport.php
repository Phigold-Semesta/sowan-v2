<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KunjunganExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Mengambil data koleksi
     */
    public function collection()
    {
        return $this->data->map(function($row, $index) {
            return [
                $index + 1,
                $row->waktu_masuk,
                $row->tamu->nama_tamu ?? '-',
                $row->tamu->instansi ?? ($row->tamu->nama_instansi ?? '-'),
                $row->layanan->nama_layanan ?? '-',
                $row->petugas->nama_petugas ?? '-',
                $row->status,
            ];
        });
    }

    /**
     * Baris Header
     */
    public function headings(): array
    {
        return [
            ['LAPORAN KUNJUNGAN TAMU - SOWAN V2'], // Baris 1: Judul
            ['LPSE KABUPATEN KARAWANG'],           // Baris 2: Sub-Judul
            [''],                                   // Baris 3: Spasi
            ['No', 'Waktu Masuk', 'Nama Tamu', 'Instansi', 'Layanan', 'Tujuan Petugas', 'Status'] // Baris 4: Header Tabel
        ];
    }

    /**
     * Mengatur Lebar Kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 20, // Waktu Masuk
            'C' => 30, // Nama Tamu
            'D' => 30, // Instansi
            'E' => 35, // Layanan
            'F' => 25, // Tujuan Petugas
            'G' => 20, // Status
        ];
    }

    /**
     * Styling Tabel
     */
    public function styles(Worksheet $sheet)
    {
        // Styling Judul Besar
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        
        return [
            // Style Judul
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '064E3B']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            2 => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            
            // Style Header Tabel (Baris ke-4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'], // Emerald Green
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    /**
     * Menambahkan Border dan Alignment Konten
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $cellRange = 'A4:G' . $lastRow; // Seluruh area tabel

                // Tambahkan Border ke seluruh sel data
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Mengetengahkan kolom No, Waktu, dan Status
                $event->sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B5:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Wrap text untuk kolom Layanan agar tidak meluber
                $event->sheet->getStyle('E5:E' . $lastRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}