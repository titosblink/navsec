<?php
namespace App\Observers;
use App\Models\ActivityLog;
use App\Models\Officer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class OfficerObserver {
    public function created(Officer $officer): void { $this->log('created', $officer, [], $officer->toArray()); }
    public function updated(Officer $officer): void { $this->log('updated', $officer, $officer->getOriginal(), $officer->getChanges()); }
    public function deleted(Officer $officer): void { $this->log('deleted', $officer, $officer->toArray(), []); }

    private function log(string $action, Officer $officer, array $old, array $new): void {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'user_name'  => Auth::user()?->name ?? 'System',
            'action'     => $action,
            'model_type' => 'Officer',
            'model_id'   => $officer->id,
            'description'=> ucfirst($action) . ' officer: ' . ($officer->name ?? 'unknown'),
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
            'ip_address' => Request::ip(),
        ]);
    }
}
