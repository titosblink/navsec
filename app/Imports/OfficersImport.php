<?php

namespace App\Imports;

use App\Models\Officer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class OfficersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    private int   $imported = 0;
    private int   $skipped  = 0;
    private array $rowErrors = [];
    private int   $rowNum   = 1; // incremented before processing; row 1 is heading

    public function model(array $row): ?Officer
    {
        $this->rowNum++;

        $svcNo = strtoupper(trim(
            $row['svc_no'] ?? $row['service_no'] ?? $row['service_number'] ?? ''
        ));
        $rank  = trim($row['rank'] ?? '');
        $name  = trim($row['name'] ?? $row['full_name'] ?? $row['fullname'] ?? '');

        // Skip completely blank rows silently
        if (!$svcNo && !$rank && !$name) {
            return null;
        }

        // Validate required fields
        $missing = [];
        if (!$svcNo) $missing[] = 'svc_no';
        if (!$rank)  $missing[] = 'rank';
        if (!$name)  $missing[] = 'name';

        if ($missing) {
            $this->skipped++;
            $this->rowErrors[] = "Row {$this->rowNum}: missing " . implode(', ', $missing) . '.';
            return null;
        }

        // Skip duplicates
        if (Officer::where('svc_no', $svcNo)->exists()) {
            $this->skipped++;
            $this->rowErrors[] = "Row {$this->rowNum}: {$svcNo} already exists — skipped.";
            return null;
        }

        $this->imported++;

        return new Officer([
            'svc_no'     => $svcNo,
            'rank'       => $rank,
            'name'       => $name,
            'servng_rtd' => 'S',
        ]);
    }

    public function getImported(): int  { return $this->imported; }
    public function getSkipped(): int   { return $this->skipped; }
    public function getRowErrors(): array { return $this->rowErrors; }
}
