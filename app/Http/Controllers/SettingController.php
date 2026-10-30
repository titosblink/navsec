<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize_admin();
        $settings = AppSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize_admin();
        foreach ($request->except(['_token']) as $key => $value) {
            AppSetting::set($key, $value);
        }
        return redirect()->route('settings.index')->with('success', 'Settings saved.');
    }

    private function authorize_admin(): void
    {
        if (auth()->user()->role !== 'admin') abort(403);
    }
}
