@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Health Center Visit</h2>
            <p class="text-muted mb-0">Update consultation details.</p>
        </div>
        <div>
            <a href="{{ route('health-visits.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('health-visits.update', $healthVisit) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Resident</label>
                        <select name="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                            <option value="">Select resident...</option>
                            @foreach($residents as $resident)
                                <option value="{{ $resident->id }}" {{ old('resident_id', $healthVisit->resident_id) == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('resident_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Visit Date</label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', optional($healthVisit->visit_date)->format('Y-m-d')) }}" class="form-control @error('visit_date') is-invalid @enderror" required>
                        @error('visit_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Visit Time</label>
                        <input type="time" name="visit_time" value="{{ old('visit_time', $healthVisit->visit_time) }}" class="form-control @error('visit_time') is-invalid @enderror" required>
                        @error('visit_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Service Type</label>
                        <select name="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                            <option value="">Select service...</option>
                            @foreach(App\Models\BarangayHealthVisit::serviceTypes() as $service)
                                <option value="{{ $service }}" {{ old('service_type', $healthVisit->service_type) === $service ? 'selected' : '' }}>{{ $service }}</option>
                            @endforeach
                        </select>
                        @error('service_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Urgent?</label>
                        <select name="is_urgent" class="form-select" >
                            <option value="0" {{ old('is_urgent', $healthVisit->is_urgent) ? '' : 'selected' }}>No</option>
                            <option value="1" {{ old('is_urgent', $healthVisit->is_urgent) ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select status...</option>
                            @foreach(App\Models\BarangayHealthVisit::statuses() as $status)
                                <option value="{{ $status }}" {{ old('status', $healthVisit->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Complaints / Reason for Visit</label>
                        <textarea name="complaints" class="form-control @error('complaints') is-invalid @enderror" rows="3" required>{{ old('complaints', $healthVisit->complaints) }}</textarea>
                        @error('complaints')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Diagnosis (optional)</label>
                        <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3">{{ old('diagnosis', $healthVisit->diagnosis) }}</textarea>
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Treatment / Advice (optional)</label>
                        <textarea name="treatment" class="form-control @error('treatment') is-invalid @enderror" rows="3">{{ old('treatment', $healthVisit->treatment) }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('health-visits.show', $healthVisit) }}" class="btn btn-outline-info">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Visit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

