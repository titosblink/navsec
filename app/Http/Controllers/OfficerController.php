<?php

namespace App\Http\Controllers;

use App\Exports\OfficerImportTemplate;
use App\Exports\OfficersExport;
use App\Imports\OfficersImport;
use App\Models\ActivityLog;
use App\Models\Officer;
use App\Models\Ship;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $query = Officer::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',       'like', "%$s%")
                  ->orWhere('svc_no',   'like', "%$s%")
                  ->orWhere('rank',     'like', "%$s%")
                  ->orWhere('pres_ship','like', "%$s%")
                  ->orWhere('pre_appt', 'like', "%$s%")
                  ->orWhere('command',  'like', "%$s%");
            });
        }
        if ($request->filled('rank'))   $query->where('rank',       $request->rank);
        if ($request->filled('branch')) $query->where('branch',     $request->branch);
        if ($request->filled('status')) $query->where('servng_rtd', $request->status);

        // Sortable columns
        $sortable  = ['name','svc_no','rank','branch','pres_ship','servng_rtd'];
        $sort      = in_array($request->sort, $sortable) ? $request->sort : 'name';
        $direction = $request->direction === 'desc' ? 'desc' : 'asc';

        if ($sort === 'rank') {
            $query->orderByRaw("FIELD(rank,'ADM','VADM','RADM','CDRE','CAPT','CDR','LT/CDR','LT','SLT','AG/SLT','MIDN') " . $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        $perPage  = in_array((int)$request->per_page, [20, 50, 100]) ? (int)$request->per_page : 20;
        $officers = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html'  => view('officers.partials.table', compact('officers', 'sort', 'direction'))->render(),
                'total' => $officers->total(),
            ]);
        }

        return view('officers.index', [
            'officers'  => $officers,
            'ranks'     => Officer::ranks(),
            'branches'  => Officer::branches(),
            'sort'      => $sort,
            'direction' => $direction,
            'perPage'   => $perPage,
        ]);
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $results = Officer::where(function($query) use ($q) {
                $query->where('name',   'like', "%$q%")
                      ->orWhere('svc_no','like', "%$q%")
                      ->orWhere('rank',  'like', "%$q%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id','name','rank','svc_no','photo','servng_rtd'])
            ->map(fn($o) => [
                'id'        => $o->id,
                'name'      => $o->name,
                'rank'      => $o->rank,
                'svc_no'    => $o->svc_no,
                'status'    => $o->servng_rtd === 'R' ? 'Retired' : 'Serving',
                'photo_url' => $o->photoUrl(),
                'url'       => route('officers.show', $o->id),
            ]);

        return response()->json($results);
    }

    public function create()
    {
        return view('officers.create', [
            'ranks'   => Officer::ranks(),
            'branches'=> Officer::branches(),
            'states'  => Officer::nigerianStates(),
            'ships'   => Ship::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'svc_no'    => 'required|unique:officers,svc_no',
            'name'      => 'required|string|max:255',
            'rank'      => 'required|string',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nok_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'photo', 'nok_photo']);
        $data['photo']     = $this->uploadPhoto($request, 'photo');
        $data['nok_photo'] = $this->uploadPhoto($request, 'nok_photo');

        $officer = Officer::create($data);

        return redirect()->route('officers.show', $officer)
                         ->with('success', 'Officer record created successfully.');
    }

    public function show(Officer $officer)
    {
        $officer->load(['appointments','courses','promotions','acrs','leaves','disciplines','documents','medicals','qualifications']);

        // Track recently viewed (session, max 5)
        $recent = session('recent_officers', []);
        $recent = array_values(array_filter($recent, fn($item) => $item['id'] !== $officer->id));
        array_unshift($recent, ['id' => $officer->id, 'name' => $officer->name, 'rank' => $officer->rank]);
        session(['recent_officers' => array_slice($recent, 0, 5)]);

        // Per-officer audit trail
        $auditLog = ActivityLog::where('model_type', 'Officer')
            ->where('model_id', $officer->id)
            ->latest()
            ->limit(15)
            ->get();

        return view('officers.show', compact('officer', 'auditLog'));
    }

    public function edit(Officer $officer)
    {
        return view('officers.edit', [
            'officer' => $officer,
            'ranks'   => Officer::ranks(),
            'branches'=> Officer::branches(),
            'states'  => Officer::nigerianStates(),
            'ships'   => Ship::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Officer $officer)
    {
        $request->validate([
            'svc_no'    => 'required|unique:officers,svc_no,' . $officer->id,
            'name'      => 'required|string|max:255',
            'rank'      => 'required|string',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nok_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'photo', 'nok_photo']);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($officer->photo);
            $data['photo'] = $this->uploadPhoto($request, 'photo');
        }
        if ($request->hasFile('nok_photo')) {
            $this->deletePhoto($officer->nok_photo);
            $data['nok_photo'] = $this->uploadPhoto($request, 'nok_photo');
        }

        $officer->update($data);

        return redirect()->route('officers.show', $officer)
                         ->with('success', 'Officer record updated successfully.');
    }

    public function destroy(Officer $officer)
    {
        $this->deletePhoto($officer->photo);
        $this->deletePhoto($officer->nok_photo);
        $officer->delete();
        return redirect()->route('officers.index')
                         ->with('success', 'Officer record deleted.');
    }

    // ── Import / Template ──

    public function importForm()
    {
        return view('officers.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        $import = new OfficersImport();
        Excel::import($import, $request->file('file'));

        return redirect()->route('officers.import.form')->with('import_result', [
            'imported' => $import->getImported(),
            'skipped'  => $import->getSkipped(),
            'errors'   => $import->getRowErrors(),
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new OfficerImportTemplate(), 'officers_import_template.xlsx');
    }

    public function downloadPdf(Officer $officer)
    {
        $officer->load(['promotions','appointments','courses','qualifications']);
        $pdf = Pdf::loadView('officers.pdf', compact('officer'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download($officer->svc_no . '-' . Str::slug($officer->name) . '.pdf');
    }

    public function bulkAction(Request $request)
    {
        $request->validate(['action' => 'required|in:export,retire', 'ids' => 'required|array']);
        $ids = $request->ids;

        if ($request->action === 'export') {
            return Excel::download(
                new OfficersExport(['ids' => $ids]),
                'officers-selected-' . now()->format('Ymd') . '.xlsx'
            );
        }

        if ($request->action === 'retire') {
            if (auth()->user()->role !== 'admin') abort(403);
            Officer::whereIn('id', $ids)->update(['servng_rtd' => 'R']);
            return response()->json(['message' => count($ids) . ' officer(s) marked as Retired.']);
        }
    }

    public function quickUpdate(Request $request, Officer $officer)
    {
        $allowed = ['pres_ship', 'command', 'pre_appt', 'e_mail_add', 'tel_no', 'servng_rtd', 'pre_addr'];
        $field   = $request->input('field');

        if (!in_array($field, $allowed)) {
            return response()->json(['error' => 'Field not editable'], 422);
        }

        $request->validate([$field => 'nullable|string|max:255']);
        $officer->update([$field => $request->input('value') ?: null]);

        return response()->json(['message' => ucfirst(str_replace('_', ' ', $field)) . ' updated.']);
    }

    public function compare(\Illuminate\Http\Request $request)
    {
        $ids      = array_filter((array) $request->input('ids', []));
        $officers = $ids ? Officer::whereIn('id', array_slice($ids, 0, 4))->get() : collect();
        $all      = Officer::orderBy('name')->get(['id','name','rank','svc_no']);

        return view('officers.compare', compact('officers', 'all'));
    }

    public function idCard(Officer $officer)
    {
        return view('officers.id-card', compact('officer'));
    }

    public function timeline(Officer $officer)
    {
        $events = collect();

        // Commission
        if ($officer->date_comm) $events->push(['date'=>$officer->date_comm,'type'=>'Commission','icon'=>'fa-star','color'=>'text-amber-600','bg'=>'bg-amber-100','title'=>'Commissioned as '.$officer->rank,'subtitle'=>$officer->type_comm]);

        // Promotions
        foreach ($officer->promotions as $p) $events->push(['date'=>$p->effective_date,'type'=>'Promotion','icon'=>'fa-arrow-up','color'=>'text-green-600','bg'=>'bg-green-100','title'=>$p->from_rank.' → '.$p->to_rank,'subtitle'=>$p->approved_by]);

        // Appointments
        foreach ($officer->appointments as $a) $events->push(['date'=>$a->date_from,'type'=>ucfirst($a->type),'icon'=>'fa-right-left','color'=>'text-blue-600','bg'=>'bg-blue-100','title'=>$a->appointment,'subtitle'=>trim(($a->ship??'').($a->command?' · '.$a->command:''))]);

        // Courses
        foreach ($officer->courses as $c) $events->push(['date'=>$c->date,'type'=>'Course','icon'=>'fa-graduation-cap','color'=>'text-purple-600','bg'=>'bg-purple-100','title'=>$c->course_name??$c->course_type,'subtitle'=>'Grade: '.($c->grade??'—')]);

        // Leaves
        foreach ($officer->leaves as $l) $events->push(['date'=>$l->start_date,'type'=>'Leave','icon'=>'fa-calendar-check','color'=>'text-amber-600','bg'=>'bg-amber-100','title'=>ucfirst($l->leave_type).' Leave ('.$l->days.' days)','subtitle'=>ucfirst($l->status)]);

        // ACRs
        foreach ($officer->acrs as $a) $events->push(['date'=>$a->date_submitted??now()->setYear($a->report_year),'type'=>'ACR','icon'=>'fa-clipboard','color'=>'text-green-600','bg'=>'bg-green-100','title'=>'Annual Confidential Report '.$a->report_year,'subtitle'=>'Rating: '.($a->overall_rating??'—').'/9 · Fitness: '.($a->fitness_category??'—')]);

        // Medical
        foreach ($officer->medicals as $m) $events->push(['date'=>$m->exam_date,'type'=>'Medical','icon'=>'fa-heartbeat','color'=>'text-pink-600','bg'=>'bg-pink-100','title'=>'Medical Examination — '.ucfirst($m->fitness_status),'subtitle'=>($m->medical_category??'').($m->hospital_facility?' · '.$m->hospital_facility:'')]);

        $events = $events->filter(fn($e)=>!empty($e['date']))->sortByDesc('date')->values();
        $officer->loadMissing(['promotions','appointments','courses','leaves','acrs','medicals']);
        return view('officers.timeline', compact('officer','events'));
    }

    private function uploadPhoto(Request $request, string $field): ?string
    {
        if (!$request->hasFile($field)) return null;

        $file = $request->file($field);
        $dest = public_path('uploads/officers');
        File::ensureDirectoryExists($dest);

        $filename = Str::uuid() . '.jpg';
        $fullPath = $dest . '/' . $filename;

        // Compress + resize with GD (max 400×400, JPEG 85%)
        if (extension_loaded('gd')) {
            $raw = file_get_contents($file->getRealPath());
            $src = @imagecreatefromstring($raw);
            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                $max = 400;
                if ($w > $max || $h > $max) {
                    $ratio = min($max / $w, $max / $h);
                    $nw    = (int)($w * $ratio);
                    $nh    = (int)($h * $ratio);
                    $dst   = imagecreatetruecolor($nw, $nh);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
                    imagedestroy($src);
                    $src = $dst;
                }
                imagejpeg($src, $fullPath, 85);
                imagedestroy($src);
                return $filename;
            }
        }

        // Fallback: move as-is
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($dest, $filename);
        return $filename;
    }

    private function deletePhoto(?string $filename): void
    {
        if (!$filename) return;
        $path = public_path('uploads/officers/' . $filename);
        if (File::exists($path)) File::delete($path);
    }
}
