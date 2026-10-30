<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    public function index(Request $request)
    {
        $query = Ship::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',    'like', "%$s%")
                  ->orWhere('code',    'like', "%$s%")
                  ->orWhere('command', 'like', "%$s%")
                  ->orWhere('type',    'like', "%$s%");
            });
        }
        $ships = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('ships.index', compact('ships'));
    }

    public function create()
    {
        return view('ships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:ships,code',
        ]);
        Ship::create($request->except(['_token']));
        return redirect()->route('ships.index')->with('success', 'Ship/Command added.');
    }

    public function edit(Ship $ship)
    {
        return view('ships.edit', compact('ship'));
    }

    public function update(Request $request, Ship $ship)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:ships,code,' . $ship->id,
        ]);
        $ship->update($request->except(['_token', '_method']));
        return redirect()->route('ships.index')->with('success', 'Ship/Command updated.');
    }

    public function destroy(Ship $ship)
    {
        $ship->delete();
        return redirect()->route('ships.index')->with('success', 'Ship/Command deleted.');
    }
}
