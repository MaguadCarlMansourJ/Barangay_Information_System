@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Health Center Visit Details</h2>
            <p class="text-muted mb-0">{{ $healthVisit->visit_number }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('health-visits.edit', $healthVisit) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('health-visits.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Patient / Visit Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small mb-1">Resident</div>
                        <div class="fw-semibold">{{ $healthVisit->resident->full_name ?? 'N/A' }}</div>
                        <div class="text-muted small">{{ $healthVisit->resident->household->purok->name ?? '' }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Service</div>
                            <div class="fw-semibold">{{ $healthVisit->service_type }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Date</div>
                            <div class="fw-semibold">{{ optional($healthVisit->visit_date)->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Time</div>
                            <div class="fw-semibold">{{ $healthVisit->visit_time }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="text-muted small mb-1">Complaints / Reason</div>
                        <div class="fw-semibold">{!! nl2br(e($healthVisit->complaints)) !!}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small mb-1">Diagnosis</div>
                        <div class="fw-semibold">{{ $healthVisit->diagnosis ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-muted small mb-1">Treatment / Advice</div>
                        <div class="fw-semibold">{{ $healthVisit->treatment ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Administration</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @php
                            $badge = match($healthVisit->status) {
                                'Done' => 'success',
                                'Scheduled' => 'primary',
                                'Cancelled' => 'secondary',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $healthVisit->status }}</span>
                        <div class="text-muted small mt-2">Urgent: {{ $healthVisit->is_urgent ? 'Yes' : 'No' }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small mb-1">Attended By</div>
                        <div class="fw-semibold">{{ $healthVisit->attendedByUser->name ?? '—' }}</div>
                    </div>

                    <div class="text-muted small mb-1">Created</div>
                    <div class="fw-semibold">{{ $healthVisit->created_at?->format('M d, Y H:i') }}</div>

                    <div class="text-muted small mb-1 mt-3">Last Update</div>
                    <div class="fw-semibold">{{ $healthVisit->updated_at?->format('M d, Y H:i') }}</div>

                    <hr>

                    <form method="POST" action="{{ route('health-visits.destroy', $healthVisit) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Delete this visit?')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

