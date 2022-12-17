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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiUserExport implements FromCollection, WithStrictNullComparison, WithColumnFormatting, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithMapping
{
    protected $year;
    protected $month;
    protected $name;
    protected $tableData;

    public function __construct(string $year, string $month, string $name, Collection $presensiUser)
    {
        $this->year = $year;
        $this->month = $month;
        $this->name = $name;
        $this->tableData = $presensiUser;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Check in', 'Check out', 'Lokasi Check in', 'Lokasi Check out',
            'Koordinat Check in', 'Koordinat Check out', 'WFO/WFH', 'Kehadiran',
            'Keterangan', 'Plan Check in', 'Plan Check out', 'Persentasi Progress'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', "{$this->name} ({$this->month} {$this->year})");
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:M2')->applyFromArray($styleArray);

        $styleArray = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'CECECECE'],
            ],
        ];
        $sheet->getStyle('A2:M2')->applyFromArray($styleArray);
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
        $is_ontime = $tableData['is_ontime'] ? 'Tepat waktu' : '';
        $is_late = $tableData['is_late'] ? 'Terlambat' : '';
        $is_early = $tableData['is_early'] ? 'Pulang cepat' : '';
        $is_wfo = $tableData['is_wfo'] ? 'WFO' : 'WFH';
        $is_cuti = $tableData['is_cuti'] ? 'Cuti' : '';
        $is_weekend = $tableData['is_weekend'] ? 'Penghujung minggu' : '';
        $is_holiday = $tableData['is_holiday'] ? 'Hari libur' : '';
        $reasonsList = [$is_ontime, $is_late, $is_early, $is_cuti, $is_weekend, $is_holiday];
        $reasons = collect($reasonsList)->filter()->join(', ');

        return [
            Carbon::parse($tableData['date'])->tz('Asia/Jakarta')->format('d'),
            $tableData['check_in'] ? Carbon::parse($tableData['check_in'])->tz('Asia/Jakarta')->format('H:i:s') : 'Invalid date',
            $tableData['check_out'] ? Carbon::parse($tableData['check_out'])->tz('Asia/Jakarta')->format('H:i:s') : 'Invalid date',
            $tableData['alamat_checkin'],
            $tableData['alamat_checkout'],
            $tableData['latlong_checkin'],
            $tableData['latlong_checkout'],
            $is_wfo,
            'Hadir',
            $reasons,
            $tableData['plan_checkin'],
            $tableData['plan_checkout'],
            $tableData['progress']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function collection()
    {
        return new Collection($this->tableData);
    }
}
