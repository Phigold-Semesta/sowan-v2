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
    protected $title;

    // Tambahkan parameter $title agar judul Excel dinamis sesuai filter
    public function __construct($data, $title = 'LAPORAN KUNJUNGAN TAMU')
    {
        $this->data = $data;
        $this->title = strtoupper($title);
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
            [$this->title . ' - SOWAN V2'],       // Baris 1: Judul Dinamis
            ['LPSE KABUPATEN KARAWANG'],          // Baris 2: Sub-Judul
            ['Dicetak pada: ' . now()->format('d F Y H:i')], // Baris 3: Info Waktu Cetak
            [''],                                 // Baris 4: Spasi
            ['No', 'Waktu Masuk', 'Nama Tamu', 'Instansi', 'Layanan', 'Tujuan Petugas', 'Status'] // Baris 5: Header Tabel
        ];
    }

    /**
     * Mengatur Lebar Kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 22, // Waktu Masuk
            'C' => 35, // Nama Tamu
            'D' => 35, // Instansi
            'E' => 40, // Layanan
            'F' => 30, // Tujuan Petugas
            'G' => 20, // Status
        ];
    }

    /**
     * Styling Tabel
     */
    public function styles(Worksheet $sheet)
    {
        // Merge Cells untuk Judul agar ke tengah
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        
        return [
            // Style Judul Utama (Luxurious Emerald)
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '064E3B']], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style Sub-Judul
            2 => [
                'font' => ['bold' => true, 'size' => 12], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            // Style Tanggal Cetak
            3 => [
                'font' => ['italic' => true, 'size' => 10], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            
            // Style Header Tabel (Baris ke-5 karena ada penambahan info cetak)
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'], // Emerald Green SOWAN
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
            ],
        ];
    }

    /**
     * Menambahkan Border, Alignment, dan Zebra Cross (Stripes)
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $cellRange = 'A5:G' . $lastRow; // Area tabel dimulai dari baris 5

                // 1. Tambahkan Border ke seluruh sel data
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

                // 2. Mengetengahkan kolom tertentu (No, Waktu, Status)
                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B6:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // 3. Wrap text untuk kolom Nama, Instansi, dan Layanan agar rapi
                $event->sheet->getStyle('C6:F' . $lastRow)->getAlignment()->setWrapText(true);

                // 4. Baris Tinggi (Row Height) agar terlihat lega dan mewah
                for ($i = 6; $i <= $lastRow; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(25);
                }
                $event->sheet->getRowDimension(5)->setRowHeight(30); // Tinggi Header

                // 5. Memberikan warna background selang-seling (Zebra Stripe) agar mudah dibaca
                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A' . $i . ':G' . $i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('ECFDF5'); // Light Emerald
                    }
                }
            },
        ];
    }
}