<?php

namespace App\Exports;

use App\Models\OfficerLeave;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LeavesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Leave Records'; }

    public function query()
    {
        $q = OfficerLeave::with('officer');
        if (!empty($this->filters['status'])) $q->where('status',     $this->filters['status']);
        if (!empty($this->filters['type']))   $q->where('leave_type', $this->filters['type']);
        return $q->latest();
    }

    public function headings(): array
    {
        return ['Svc No', 'Rank', 'Officer Name', 'Leave Type', 'Start Date', 'End Date', 'Days', 'Status', 'Approved By', 'Remarks'];
    }

    public function map($leave): array
    {
        return [
            $leave->officer->svc_no   ?? '',
            $leave->officer->rank     ?? '',
            $leave->officer->name     ?? '',
            ucfirst($leave->leave_type),
            $leave->start_date->format('d/m/Y'),
            $leave->end_date->format('d/m/Y'),
            $leave->days,
            ucfirst($leave->status),
            $leave->approved_by ?? '',
            $leave->remarks     ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1D55']],
            ],
        ];
    }
}
