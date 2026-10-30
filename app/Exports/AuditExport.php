<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AuditExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $filters = []) {}

    public function title(): string { return 'Audit Log'; }

    public function query()
    {
        $q = ActivityLog::latest();
        if (!empty($this->filters['action'])) $q->where('action', $this->filters['action']);
        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $q->where(fn($q) => $q->where('description','like',"%$s%")->orWhere('user_name','like',"%$s%"));
        }
        return $q;
    }

    public function headings(): array
    {
        return ['Date / Time', 'User', 'Action', 'Description', 'Model', 'Record ID', 'IP Address'];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('d/m/Y H:i:s'),
            $log->user_name ?? '—',
            ucfirst($log->action),
            $log->description,
            $log->model_type,
            $log->model_id,
            $log->ip_address,
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
