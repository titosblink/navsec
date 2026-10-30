<?php

namespace App\Exports;

use App\Models\Officer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OfficersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Officers'; }

    public function query()
    {
        $q = Officer::query();
        if (!empty($this->filters['ids']))    $q->whereIn('id', $this->filters['ids']);
        if (!empty($this->filters['rank']))   $q->where('rank', $this->filters['rank']);
        if (!empty($this->filters['branch'])) $q->where('branch', $this->filters['branch']);
        if (!empty($this->filters['status'])) $q->where('servng_rtd', $this->filters['status']);
        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $q->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('svc_no','like',"%$s%"));
        }
        return $q->orderBy('rank')->orderBy('name');
    }

    public function headings(): array
    {
        return ['Svc No','Rank','Name','Branch','Specialisation','Present Ship','Command',
                'Current Appointment','Status','Date of Entry','Date Commissioned','DOB','Email'];
    }

    public function map($o): array
    {
        return [
            $o->svc_no, $o->rank, $o->name, $o->branch, $o->spl,
            $o->pres_ship, $o->command, $o->pre_appt,
            $o->servng_rtd === 'R' ? 'Retired' : 'Serving',
            $o->doe?->format('d/m/Y'),
            $o->date_comm?->format('d/m/Y'),
            $o->dt_birth?->format('d/m/Y'),
            $o->e_mail_add,
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
