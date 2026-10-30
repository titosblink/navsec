<?php

namespace App\Exports;

use App\Models\OfficerAppointment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PostingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Appointments'; }

    public function query()
    {
        $q = OfficerAppointment::with('officer');
        if (!empty($this->filters['date_from'])) $q->where('date_from', '>=', $this->filters['date_from']);
        if (!empty($this->filters['date_to']))   $q->where('date_from', '<=', $this->filters['date_to']);
        if (!empty($this->filters['type']))      $q->where('type', $this->filters['type']);
        return $q->latest('date_from');
    }

    public function headings(): array
    {
        return ['Svc No','Rank','Officer Name','Appointment','Ship','Command','Type','Date From','Date To','Current'];
    }

    public function map($p): array
    {
        return [
            $p->officer->svc_no ?? '', $p->officer->rank ?? '', $p->officer->name ?? '',
            $p->appointment, $p->ship, $p->command, ucfirst($p->type),
            $p->date_from?->format('d/m/Y'), $p->date_to?->format('d/m/Y'),
            $p->is_current ? 'Yes' : 'No',
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
