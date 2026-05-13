<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Purok;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $query = Household::with(['purok', 'residents']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('house_number', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('purok')) {
            $query->where('purok_id', $request->purok);
        }

        $households = $query->latest()->paginate(15)->withQueryString();
        $puroks = Purok::orderBy('name')->get();

        return view('households.index', compact('households', 'puroks'));
    }

    public function create()
    {
        $puroks = Purok::orderBy('name')->get();

        return view('households.create', compact('puroks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purok_id' => 'required|exists:puroks,id',
            'house_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Household::create($validated);

        return redirect()->route('households.index')->with('success', 'Household added successfully');
    }

    public function show(Household $household)
    {
        $household->load(['purok', 'residents']);

        return view('households.show', compact('household'));
    }

    public function edit(Household $household)
    {
        $puroks = Purok::orderBy('name')->get();

        return view('households.edit', compact('household', 'puroks'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'purok_id' => 'required|exists:puroks,id',
            'house_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $household->update($validated);

        return redirect()->route('households.index')->with('success', 'Household updated successfully');
    }

    public function destroy(Household $household)
    {
        if ($household->residents()->exists()) {
            return back()->with('success', 'Household cannot be deleted while it has residents.');
        }

        $household->delete();

        return redirect()->route('households.index')->with('success', 'Household deleted successfully');
    }
}
