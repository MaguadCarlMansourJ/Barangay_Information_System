@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Resident Profile</h2>
            <p class="text-muted mb-0">Complete profiling record and activity history</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Profile
            </a>
            <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->full_name) }}&size=120&background=667eea&color=fff" class="rounded-circle mb-3" alt="{{ $resident->full_name }}">
                    <h4 class="mb-1">{{ $resident->full_name }}</h4>
                    <span class="badge bg-{{ $resident->is_active ? 'success' : 'danger' }}">
                        {{ $resident->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="border-top mt-4 pt-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Resident ID</span>
                            <span class="fw-semibold">#{{ $resident->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Age</span>
                            <span class="fw-semibold">{{ $resident->age }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Gender</span>
                            <span class="fw-semibold">{{ $resident->gender }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Civil Status</span>
                            <span class="fw-semibold">{{ $resident->civil_status }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted">Voter</span>
                            <span class="fw-semibold">{{ $resident->is_registered_voter ? 'Registered' : 'Not registered' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Household</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>House No.:</strong> {{ $resident->household->house_number ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Head:</strong> {{ $resident->household->head_name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Purok:</strong> {{ $resident->household->purok->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Address:</strong> {{ $resident->household->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Birthdate</small>
                            <span class="fw-semibold">{{ $resident->birthdate->format('M d, Y') }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Place of Birth</small>
                            <span class="fw-semibold">{{ $resident->place_of_birth ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Citizenship</small>
                            <span class="fw-semibold">{{ $resident->citizenship ?? 'Filipino' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Religion</small>
                            <span class="fw-semibold">{{ $resident->religion ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Educational Attainment</small>
                            <span class="fw-semibold">{{ $resident->educational_attainment ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Relationship to Household Head</small>
                            <span class="fw-semibold">{{ $resident->relationship_to_household_head ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Occupation</small>
                            <span class="fw-semibold">{{ $resident->occupation ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Contact Number</small>
                            <span class="fw-semibold">{{ $resident->contact_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <span class="fw-semibold">{{ $resident->email ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">PhilHealth ID</small>
                            <span class="fw-semibold">{{ $resident->philhealth_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date of Residency</small>
                            <span class="fw-semibold">{{ $resident->date_of_residency ? $resident->date_of_residency->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Voter Precinct</small>
                            <span class="fw-semibold">{{ $resident->voter_precinct_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date Registered</small>
                            <span class="fw-semibold">{{ $resident->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Sector Classification</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-{{ $resident->is_senior_citizen ? 'success' : 'secondary' }}">Senior Citizen</span>
                        <span class="badge bg-{{ $resident->is_pwd ? 'success' : 'secondary' }}">PWD{{ $resident->pwd_id_number ? ': ' . $resident->pwd_id_number : '' }}</span>
                        <span class="badge bg-{{ $resident->is_solo_parent ? 'success' : 'secondary' }}">Solo Parent{{ $resident->solo_parent_id_number ? ': ' . $resident->solo_parent_id_number : '' }}</span>
                        <span class="badge bg-{{ $resident->is_4ps_beneficiary ? 'success' : 'secondary' }}">4Ps Beneficiary</span>
                        <span class="badge bg-{{ $resident->is_indigenous_person ? 'success' : 'secondary' }}">Indigenous Person</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0">Document Requests</h5>
                        </div>
                        <div class="card-body">
                            @forelse($resident->documentRequests as $request)
                                <div class="border-bottom pb-2 mb-2">
                                    <div class="fw-semibold">{{ $request->documentType->name ?? 'Document' }}</div>
                                    <small class="text-muted">{{ $request->status ?? 'N/A' }}</small>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No document requests recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0">Event Participation</h5>
                        </div>
                        <div class="card-body">
                            @forelse($resident->eventParticipants as $participant)
                                <div class="border-bottom pb-2 mb-2">
                                    <div class="fw-semibold">{{ $participant->event->title ?? 'Event' }}</div>
                                    <small class="text-muted">{{ $participant->attendance_status ?? 'Registered' }}</small>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No event participation recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
