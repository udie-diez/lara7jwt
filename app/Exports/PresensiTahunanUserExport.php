<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiTahunanUserExport implements FromCollection, WithStrictNullComparison, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithMapping
{
    protected $year;
    protected $user;
    protected $tableData;

    public function __construct(Collection $presensiTahunanUser)
    {
        $this->year = $presensiTahunanUser['year'];
        $this->user = $presensiTahunanUser['user'];
        $this->tableData = $presensiTahunanUser['rows'];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return ['Bulan', 'Hadir', 'Tepat Waktu', 'Terlambat', 'Pulang Cepat', 'Tidak Hadir', 'Normalisasi'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', $this->year . ' ' . $this->user);
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
        return [];
    }

    public function map($tableData): array
    {
        return [
            $tableData->month,
            $tableData->presence,
            $tableData->on_time,
            $tableData->late,
            $tableData->fast_leave,
            $tableData->absence,
            $tableData->normalization,
        ];
    }

    public function collection()
    {
        return new Collection($this->tableData);
    }
}
