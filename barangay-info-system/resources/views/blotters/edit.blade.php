@extends('layouts.app')

@section('content')
@php
    $complainant = $blotter->blotterParties->firstWhere('party_type', 'Complainant');
    $respondent = $blotter->blotterParties->firstWhere('party_type', 'Respondent');
@endphp

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Blotter Case</h3>
            <p class="text-muted mb-0">{{ $blotter->blotter_number }}</p>
        </div>
        <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('blotters.update', $blotter) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            @foreach(['Complaint', 'Incident', 'Dispute'] as $type)
                                <option value="{{ $type }}" {{ old('type', $blotter->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['Open', 'Under Investigation', 'Resolved', 'Closed'] as $status)
                                <option value="{{ $status }}" {{ old('status', $blotter->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Incident Date</label>
                        <input type="date" name="incident_date" class="form-control" value="{{ old('incident_date', $blotter->incident_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Incident Time</label>
                        <input type="time" name="incident_time" class="form-control" value="{{ old('incident_time', substr($blotter->incident_time, 0, 5)) }}" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Incident Location</label>
                        <input type="text" name="incident_location" class="form-control" value="{{ old('incident_location', $blotter->incident_location) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $blotter->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Complainant</label>
                        <select name="complainant_id" class="form-select">
                            <option value="">Walk-in / Not encoded resident</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}" {{ old('complainant_id', optional($complainant)->resident_id) == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Respondent</label>
                        <select name="respondent_id" class="form-select">
                            <option value="">Unknown / Not encoded resident</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}" {{ old('respondent_id', optional($respondent)->resident_id) == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Complainant Statement</label>
                        <textarea name="complainant_statement" class="form-control" rows="3">{{ old('complainant_statement', optional($complainant)->statement) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Respondent Statement</label>
                        <textarea name="respondent_statement" class="form-control" rows="3">{{ old('respondent_statement', optional($respondent)->statement) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Resolution / Action Taken</label>
                        <textarea name="resolution" class="form-control" rows="3">{{ old('resolution', $blotter->resolution) }}</textarea>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('blotters.show', $blotter) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Blotter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
