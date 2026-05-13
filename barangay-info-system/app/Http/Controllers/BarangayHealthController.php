<?php

namespace App\Http\Controllers;

use App\Models\BarangayHealthVisit;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BarangayHealthController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangayHealthVisit::with(['resident']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) like ?", ["%{$search}%"]);
            });
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('visit_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('visit_date', '<=', $request->to);
        }

        $visits = $query->latest('visit_date')->paginate(15);

        // Keep query params in pagination links
        $visits->appends($request->query());

        $serviceTypes = BarangayHealthVisit::serviceTypes();
        $statuses = BarangayHealthVisit::statuses();

        return view('health_visits.index', compact('visits', 'serviceTypes', 'statuses'));
    }




    public function create()
    {
        $residents = Resident::query()
            ->orderBy('last_name')
            ->get();

        return view('health_visits.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'visit_date' => 'required|date',
            'visit_time' => 'required',
            'service_type' => ['required', Rule::in(array_values(BarangayHealthVisit::serviceTypes()))],
            'complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'is_urgent' => 'sometimes|boolean',
            'status' => ['required', Rule::in(array_values(BarangayHealthVisit::statuses()))],
        ]);

        $validated['attended_by'] = auth()->id();
        $validated['is_urgent'] = $request->boolean('is_urgent');

        $validated['visit_number'] = $this->generateVisitNumber();

        BarangayHealthVisit::create($validated);

        return redirect()->route('health-visits.index')->with('success', 'Health center visit saved successfully');
    }

    public function show(BarangayHealthVisit $healthVisit)
    {
        $healthVisit->load(['resident', 'attendedByUser']);
        $healthVisit->loadMissing('resident.household.purok');
        return view('health_visits.show', compact('healthVisit'));
    }

    public function edit(BarangayHealthVisit $healthVisit)
    {
        $residents = Resident::query()->orderBy('last_name')->get();

        return view('health_visits.edit', compact('healthVisit', 'residents'));
    }

    public function update(Request $request, BarangayHealthVisit $healthVisit)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'visit_date' => 'required|date',
            'visit_time' => 'required',
            'service_type' => ['required', Rule::in(array_values(BarangayHealthVisit::serviceTypes()))],
            'complaints' => 'required|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'is_urgent' => 'sometimes|boolean',
            'status' => ['required', Rule::in(array_values(BarangayHealthVisit::statuses()))],
        ]);

        $validated['is_urgent'] = $request->boolean('is_urgent');
        $validated['attended_by'] = auth()->id();

        $healthVisit->update($validated);

        return redirect()->route('health-visits.index')->with('success', 'Health center visit updated successfully');
    }

    public function destroy(BarangayHealthVisit $healthVisit)
    {
        $healthVisit->delete();

        return redirect()->route('health-visits.index')->with('success', 'Health center visit deleted successfully');
    }

    private function generateVisitNumber(): string
    {
        // Example: BHC-2026-000001
        $today = now()->format('Y');
        $random = strtoupper(Str::random(6));
        return 'BHC-' . $today . '-' . $random;
    }
}
