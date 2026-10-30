<?php

namespace App\Http\Controllers;

use App\Exports\AuditExport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $this->requireAdmin();
        $query = ActivityLog::latest();
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('description','like',"%$s%")->orWhere('user_name','like',"%$s%"));
        }
        $logs = $query->paginate(50)->withQueryString();
        return view('audits.index', compact('logs'));
    }

    public function export(Request $request)
    {
        $this->requireAdmin();
        $filename = 'audit-log-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new AuditExport($request->only(['action','search'])), $filename);
    }

    private function requireAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
    }
}
