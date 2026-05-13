@extends('layouts.resident')

@section('content')
<h2 class="mb-4">My Barangay Profile</h2>

<div class="portal-card p-4">
    <div class="row g-3">
        <div class="col-md-4"><small class="text-muted">Full Name</small><div class="fw-semibold">{{ $resident->full_name }}</div></div>
        <div class="col-md-4"><small class="text-muted">Birthdate / Age</small><div class="fw-semibold">{{ $resident->birthdate->format('M d, Y') }} | {{ $resident->age }}</div></div>
        <div class="col-md-4"><small class="text-muted">Gender</small><div class="fw-semibold">{{ $resident->gender }}</div></div>
        <div class="col-md-4"><small class="text-muted">Household</small><div class="fw-semibold">{{ $resident->household->house_number ?? 'N/A' }}</div></div>
        <div class="col-md-4"><small class="text-muted">Purok</small><div class="fw-semibold">{{ $resident->household->purok->name ?? 'N/A' }}</div></div>
        <div class="col-md-4"><small class="text-muted">Address</small><div class="fw-semibold">{{ $resident->household->address ?? 'N/A' }}</div></div>
        <div class="col-md-4"><small class="text-muted">Civil Status</small><div class="fw-semibold">{{ $resident->civil_status }}</div></div>
        <div class="col-md-4"><small class="text-muted">Citizenship</small><div class="fw-semibold">{{ $resident->citizenship ?? 'Filipino' }}</div></div>
        <div class="col-md-4"><small class="text-muted">Voter Status</small><div class="fw-semibold">{{ $resident->is_registered_voter ? 'Registered' : 'Not registered' }}</div></div>
    </div>

    <hr>

    <div class="d-flex flex-wrap gap-2">
        <span class="badge text-bg-{{ $resident->is_senior_citizen ? 'success' : 'secondary' }}">Senior Citizen</span>
        <span class="badge text-bg-{{ $resident->is_pwd ? 'success' : 'secondary' }}">PWD</span>
        <span class="badge text-bg-{{ $resident->is_solo_parent ? 'success' : 'secondary' }}">Solo Parent</span>
        <span class="badge text-bg-{{ $resident->is_4ps_beneficiary ? 'success' : 'secondary' }}">4Ps</span>
        <span class="badge text-bg-{{ $resident->is_indigenous_person ? 'success' : 'secondary' }}">Indigenous Person</span>
    </div>
</div>
@endsection
