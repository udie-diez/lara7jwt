<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CutiUserExport implements FromCollection, WithStrictNullComparison, WithColumnFormatting, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithMapping
{
    protected $year;
    protected $name;
    protected $tableData;

    public function __construct(string $year, string $name, Collection $cutiUser)
    {
        $this->year = $year;
        $this->name = $name;
        $this->tableData = $cutiUser;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'Tanggal Pengajuan', 'Jenis Cuti', 'Alasan', 'Durasi',
            'Cuti Tahunan Terpakai', 'Lampiran', 'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', "{$this->year} ({$this->name})");
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:G2')->applyFromArray($styleArray);

        $styleArray = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'CECECECE'],
            ],
        ];
        $sheet->getStyle('A2:G2')->applyFromArray($styleArray);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                $event->sheet->getDelegate()
                    ->getStyle("A2:{$highestColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '00000000']
                            ],
                        ],
                    ]);
            },
        ];
    }

    public function map($tableData): array
    {
        $duration = explode(',', $tableData['date']);

        return [
            $tableData['createdAt'] ? Carbon::parse($tableData['createdAt'])->tz('Asia/Jakarta')->format('Y-m-d H:i:s') : 'Invalid date',
            $tableData['jenis_cuti'],
            $tableData['keterangan'],
            count($duration),
            $tableData['annualLeaveSpend'],
            $tableData['dokumen'],
            $tableData['is_approve'],
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }

    public function collection()
    {
        return new Collection($this->tableData);
    }
}
