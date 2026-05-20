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

    public function __construct($data, $title = 'LAPORAN KUNJUNGAN TAMU')
    {
        $this->data = $data;
        $this->title = strtoupper($title);
    }

    /**
     * Mengambil data koleksi dengan memastikan tipe data adalah Collection
     */
    public function collection()
    {
        // Memastikan $this->data adalah koleksi agar method map() tersedia
        return collect($this->data)->map(function($row, $index) {
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
            [$this->title . ' - SOWAN V2'],
            ['LPSE KABUPATEN KARAWANG'],
            ['Dicetak pada: ' . now()->format('d F Y H:i')],
            [''],
            ['No', 'Waktu Masuk', 'Nama Tamu', 'Instansi', 'Layanan', 'Tujuan Petugas', 'Status']
        ];
    }

    /**
     * Mengatur Lebar Kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 22,
            'C' => 35,
            'D' => 35,
            'E' => 40,
            'F' => 30,
            'G' => 20,
        ];
    }

    /**
     * Styling Tabel
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '064E3B']], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            3 => [
                'font' => ['italic' => true, 'size' => 10], 
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'],
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
                if ($lastRow < 5) return; // Proteksi jika data kosong
                
                $cellRange = 'A5:G' . $lastRow;

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

                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B6:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('G6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $event->sheet->getStyle('C6:F' . $lastRow)->getAlignment()->setWrapText(true);

                for ($i = 6; $i <= $lastRow; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(25);
                }
                $event->sheet->getRowDimension(5)->setRowHeight(30);

                for ($i = 6; $i <= $lastRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle('A' . $i . ':G' . $i)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('ECFDF5');
                    }
                }
            },
        ];
    }
}