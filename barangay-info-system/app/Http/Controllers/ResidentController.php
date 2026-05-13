<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Household;
use App\Models\Purok;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::with('household.purok');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('purok')) {
            $query->whereHas('household', function ($q) use ($request) {
                $q->where('purok_id', $request->purok);
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('age')) {
            [$minAge, $maxAge] = match ($request->age) {
                // Regular voter / Barangay Captain & Kagawad candidates
                '18+' => [18, null],
                // SK voter (15–30)
                '15-30' => [15, 30],
                // SK Chairperson & SK Kagawad candidates (18–24)
                '18-24' => [18, 24],
                default => [null, null],
            };

            if ($minAge !== null) {
                $maxBirthdate = now()->subYears($minAge)->endOfDay();
                $query->whereDate('birthdate', '<=', $maxBirthdate);
            }

            if ($maxAge !== null) {
                $minBirthdate = now()->subYears($maxAge + 1)->addDay()->startOfDay();
                $query->whereDate('birthdate', '>=', $minBirthdate);
            }
        }

        $residents = $query->latest()->paginate(15);
        $puroks = Purok::orderBy('name')->get();

        return view('residents.index', compact('residents', 'puroks'));
    }

    public function create()
    {
        $households = Household::with('purok')->get();
        return view('residents.create', compact('households'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_id' => 'required|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date',
            'place_of_birth' => 'nullable|string|max:255',
            'citizenship' => 'required|string|max:100',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|string',
            'relationship_to_household_head' => 'nullable|string|max:100',
            'date_of_residency' => 'nullable|date',
            'is_registered_voter' => 'boolean',
            'voter_precinct_number' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => ['nullable', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'nullable|email',
            'philhealth_id' => 'nullable|string|max:255',
            'is_senior_citizen' => 'boolean',
            'is_pwd' => 'boolean',
            'pwd_id_number' => 'nullable|string|max:100',
            'is_solo_parent' => 'boolean',
            'solo_parent_id_number' => 'nullable|string|max:100',
            'is_4ps_beneficiary' => 'boolean',
            'is_indigenous_person' => 'boolean',
        ]);

        $validated['is_registered_voter'] = $request->boolean('is_registered_voter');
        $validated['is_senior_citizen'] = $request->boolean('is_senior_citizen');
        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_4ps_beneficiary'] = $request->boolean('is_4ps_beneficiary');
        $validated['is_indigenous_person'] = $request->boolean('is_indigenous_person');

        Resident::create($validated);
        
        // Update household member count
        $household = Household::find($request->household_id);
        $household->update(['member_count' => $household->residents()->count()]);

        return redirect()->route('residents.index')->with('success', 'Resident added successfully');
    }

    public function edit(Resident $resident)
    {
        $households = Household::with('purok')->get();
        return view('residents.edit', compact('resident', 'households'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'household_id' => 'required|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date',
            'place_of_birth' => 'nullable|string|max:255',
            'citizenship' => 'required|string|max:100',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|string',
            'relationship_to_household_head' => 'nullable|string|max:100',
            'date_of_residency' => 'nullable|date',
            'is_registered_voter' => 'boolean',
            'voter_precinct_number' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:255',
            'contact_number' => ['nullable', 'regex:/^(09|\+639)\d{9}$/'],
            'email' => 'nullable|email',
            'philhealth_id' => 'nullable|string|max:255',
            'is_senior_citizen' => 'boolean',
            'is_pwd' => 'boolean',
            'pwd_id_number' => 'nullable|string|max:100',
            'is_solo_parent' => 'boolean',
            'solo_parent_id_number' => 'nullable|string|max:100',
            'is_4ps_beneficiary' => 'boolean',
            'is_indigenous_person' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $oldHousehold = $resident->household;
        $validated['is_registered_voter'] = $request->boolean('is_registered_voter');
        $validated['is_senior_citizen'] = $request->boolean('is_senior_citizen');
        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_4ps_beneficiary'] = $request->boolean('is_4ps_beneficiary');
        $validated['is_indigenous_person'] = $request->boolean('is_indigenous_person');
        $validated['is_active'] = $request->boolean('is_active');

        $resident->update($validated);
        
        // Update household member count for both old and new household
        if ($oldHousehold && $oldHousehold->id !== (int) $validated['household_id']) {
            $oldHousehold->update(['member_count' => $oldHousehold->residents()->count()]);
        }
        $resident->household->update(['member_count' => $resident->household->residents()->count()]);
        
        return redirect()->route('residents.index')->with('success', 'Resident updated successfully');
    }

    public function destroy(Resident $resident)
    {
        $household = $resident->household;
        $resident->delete();
        $household->update(['member_count' => $household->residents()->count()]);
        
        return redirect()->route('residents.index')->with('success', 'Resident deleted successfully');
    }

    public function show(Resident $resident)
    {
        $resident->load(['household.purok', 'documentRequests.documentType', 'eventParticipants.event']);
        return view('residents.show', compact('resident'));
    }

    public function export(Request $request)
    {
        $query = Resident::with('household.purok');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('purok')) {
            $query->whereHas('household', function ($q) use ($request) {
                $q->where('purok_id', $request->purok);
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $fileName = 'residents_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Full Name', 'Household', 'Purok', 'Birthdate', 'Age', 'Gender', 'Civil Status', 'Relationship to Head', 'Voter', 'Precinct', 'Senior', 'PWD', 'Solo Parent', '4Ps', 'IP', 'Contact', 'Email', 'Occupation', 'PhilHealth ID', 'Status']);

            $query->orderBy('last_name')->chunk(100, function ($residents) use ($handle) {
                foreach ($residents as $resident) {
                    fputcsv($handle, [
                        $resident->id,
                        $resident->full_name,
                        $resident->household->house_number ?? '',
                        $resident->household->purok->name ?? '',
                        $resident->birthdate ? $resident->birthdate->format('Y-m-d') : '',
                        $resident->birthdate ? $resident->age : '',
                        $resident->gender,
                        $resident->civil_status,
                        $resident->relationship_to_household_head,
                        $resident->is_registered_voter ? 'Yes' : 'No',
                        $resident->voter_precinct_number,
                        $resident->is_senior_citizen ? 'Yes' : 'No',
                        $resident->is_pwd ? 'Yes' : 'No',
                        $resident->is_solo_parent ? 'Yes' : 'No',
                        $resident->is_4ps_beneficiary ? 'Yes' : 'No',
                        $resident->is_indigenous_person ? 'Yes' : 'No',
                        $resident->contact_number,
                        $resident->email,
                        $resident->occupation,
                        $resident->philhealth_id,
                        $resident->is_active ? 'Active' : 'Inactive',
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
