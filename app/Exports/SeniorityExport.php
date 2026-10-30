<?php

namespace App\Exports;

use App\Models\Officer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SeniorityExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    public function __construct(private string $rank = '') {}

    public function title(): string { return 'Seniority List'; }

    public function query()
    {
        $q = Officer::where('servng_rtd', 'S')
            ->orderByRaw("FIELD(rank,'ADM','VADM','RADM','CDRE','CAPT','CDR','LT/CDR','LT','SLT','AG/SLT','MIDN')")
            ->orderByRaw('snrty_date IS NULL, snrty_date ASC')
            ->orderByRaw('dolp IS NULL, dolp ASC')
            ->orderByRaw('date_comm IS NULL, date_comm ASC')
            ->orderByRaw('doe IS NULL, doe ASC')
            ->orderBy('svc_no');

        if ($this->rank) $q->where('rank', $this->rank);

        return $q;
    }

    public function headings(): array
    {
        return ['#', 'Svc No', 'Rank', 'Name', 'Branch', 'Seniority Date', 'DOLP', 'Date Commissioned', 'Date of Entry', 'Present Ship / Command', 'Current Appointment'];
    }

    public function map($o): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $o->svc_no,
            $o->rank,
            $o->name,
            $o->branch,
            $o->snrty_date?->format('d/m/Y'),
            $o->dolp?->format('d/m/Y'),
            $o->date_comm?->format('d/m/Y'),
            $o->doe?->format('d/m/Y'),
            $o->pres_ship,
            $o->pre_appt,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1D55']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Add a title row above the headers
                $event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->setCellValue('A1', 'NIGERIAN NAVY — SENIORITY LIST');
                $event->sheet->getDelegate()->mergeCells('A2:K2');
                $event->sheet->getDelegate()->setCellValue('A2', 'Generated: ' . now()->format('d M Y H:i'));
                $event->sheet->getDelegate()->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '0B1D55']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $event->sheet->getDelegate()->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '888888']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                // Alternate row shading
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                for ($r = 4; $r <= $lastRow; $r++) {
                    if ($r % 2 === 0) {
                        $event->sheet->getDelegate()->getStyle("A{$r}:K{$r}")
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F0F4FF');
                    }
                }
            },
        ];
    }
}
