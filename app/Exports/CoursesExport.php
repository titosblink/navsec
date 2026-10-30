<?php

namespace App\Exports;

use App\Models\OfficerCourse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CoursesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Courses'; }

    public function query()
    {
        $q = OfficerCourse::with('officer');
        if (!empty($this->filters['course_type'])) $q->where('course_type', $this->filters['course_type']);
        if (!empty($this->filters['year']))         $q->whereYear('date', $this->filters['year']);
        return $q->latest('date');
    }

    public function headings(): array
    {
        return ['Svc No','Rank','Officer Name','Course Type','Course Name','Date','Grade','Certificate Received','Remarks'];
    }

    public function map($c): array
    {
        return [
            $c->officer->svc_no ?? '', $c->officer->rank ?? '', $c->officer->name ?? '',
            $c->course_type, $c->course_name, $c->date?->format('d/m/Y'),
            $c->grade, $c->received, $c->remarks,
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
