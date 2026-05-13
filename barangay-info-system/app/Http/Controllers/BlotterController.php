<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blotter;
use App\Models\BlotterParty;
use App\Models\Resident;

class BlotterController extends Controller
{
    public function index()
    {
        $blotters = Blotter::with(['reportedBy'])->paginate(15);
        return view('blotters.index', compact('blotters'));
    }

    public function create()
    {
        $residents = Resident::all();
        return view('blotters.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:Complaint,Incident,Dispute',
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'required|string',
            'complainant_id' => 'nullable|exists:residents,id',
            'respondent_id' => 'nullable|exists:residents,id|different:complainant_id',
            'complainant_statement' => 'nullable|string',
            'respondent_statement' => 'nullable|string',
        ]);

        $blotter = Blotter::create([
            'blotter_number' => 'BLT-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -5)),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'incident_date' => $validated['incident_date'],
            'incident_time' => $validated['incident_time'],
            'incident_location' => $validated['incident_location'],
            'status' => 'Open',
            'reported_by' => auth()->id(),
        ]);

        if (!empty($validated['complainant_id'])) {
            BlotterParty::create([
                'blotter_id' => $blotter->id,
                'resident_id' => $validated['complainant_id'],
                'party_type' => 'Complainant',
                'statement' => $validated['complainant_statement'] ?? null,
            ]);
        }

        if (!empty($validated['respondent_id'])) {
            BlotterParty::create([
                'blotter_id' => $blotter->id,
                'resident_id' => $validated['respondent_id'],
                'party_type' => 'Respondent',
                'statement' => $validated['respondent_statement'] ?? null,
            ]);
        }

        return redirect()->route('blotters.index')->with('success', 'Blotter case created successfully');
    }

    public function show(Blotter $blotter)
    {
        $blotter->load(['blotterParties.resident', 'reportedBy', 'resolvedBy']);
        return view('blotters.show', compact('blotter'));
    }

    public function edit(Blotter $blotter)
    {
        $blotter->load('blotterParties');
        $residents = Resident::orderBy('last_name')->get();

        return view('blotters.edit', compact('blotter', 'residents'));
    }

    public function update(Request $request, Blotter $blotter)
    {
        $validated = $request->validate([
            'type' => 'required|in:Complaint,Incident,Dispute',
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'incident_location' => 'required|string',
            'status' => 'required|in:Open,Under Investigation,Resolved,Closed',
            'resolution' => 'nullable|string',
            'complainant_id' => 'nullable|exists:residents,id',
            'respondent_id' => 'nullable|exists:residents,id|different:complainant_id',
            'complainant_statement' => 'nullable|string',
            'respondent_statement' => 'nullable|string',
        ]);

        $blotter->update([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'incident_date' => $validated['incident_date'],
            'incident_time' => $validated['incident_time'],
            'incident_location' => $validated['incident_location'],
            'status' => $validated['status'],
            'resolution' => $validated['resolution'] ?? null,
            'resolved_by' => in_array($validated['status'], ['Resolved', 'Closed']) ? auth()->id() : null,
            'resolved_date' => in_array($validated['status'], ['Resolved', 'Closed']) ? now() : null,
        ]);

        $this->syncParty($blotter, 'Complainant', $validated['complainant_id'] ?? null, $validated['complainant_statement'] ?? null);
        $this->syncParty($blotter, 'Respondent', $validated['respondent_id'] ?? null, $validated['respondent_statement'] ?? null);

        return redirect()->route('blotters.show', $blotter)->with('success', 'Blotter case updated successfully');
    }

    public function destroy(Blotter $blotter)
    {
        $blotter->delete();
        return redirect()->route('blotters.index')->with('success', 'Blotter case deleted successfully');
    }

    public function resolve(Request $request, Blotter $blotter)
    {
        $request->validate([
            'resolution' => 'required|string',
        ]);

        $blotter->update([
            'status' => 'Resolved',
            'resolution' => $request->resolution,
            'resolved_by' => auth()->id(),
            'resolved_date' => now(),
        ]);

        return back()->with('success', 'Blotter case resolved');
    }

    public function updateStatus(Request $request, Blotter $blotter)
    {
        $request->validate([
            'status' => 'required|in:Open,Under Investigation,Resolved,Closed',
        ]);

        $blotter->update(['status' => $request->status]);

        return back()->with('success', 'Status updated');
    }

    private function syncParty(Blotter $blotter, string $partyType, ?int $residentId, ?string $statement): void
    {
        $party = $blotter->blotterParties()->where('party_type', $partyType)->first();

        if (!$residentId) {
            if ($party) {
                $party->delete();
            }

            return;
        }

        BlotterParty::updateOrCreate(
            [
                'blotter_id' => $blotter->id,
                'party_type' => $partyType,
            ],
            [
                'resident_id' => $residentId,
                'statement' => $statement,
            ]
        );
    }
}
