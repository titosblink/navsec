<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PromotionEligibilityExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(private $eligible) {}

    public function title(): string { return 'Promotion Eligibility'; }

    public function collection() { return $this->eligible->values(); }

    public function headings(): array
    {
        return ['Rank','Name','Svc No','Branch','DOLP','Years in Rank','Min Required','Status'];
    }

    public function map($o): array
    {
        return [
            $o->rank,
            $o->name,
            $o->svc_no,
            $o->branch ?: '—',
            $o->dolp?->format('d M Y') ?: '—',
            $o->_years_in_rank . ' yrs',
            $o->_min_years . ' yrs',
            $o->_years_in_rank >= $o->_min_years + 2 ? 'Overdue' : 'Eligible',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1D55']]],
        ];
    }
}
