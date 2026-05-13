@extends('layouts.resident')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="mb-1">Resident Dashboard</h2>
        <p class="text-muted mb-0">{{ $resident->full_name }} | {{ $resident->household->purok->name ?? 'No purok assigned' }}</p>
    </div>
    <a href="{{ route('resident-portal.requests.create') }}" class="btn btn-brand">
        <i class="fas fa-plus me-1"></i> Request Document
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6"><div class="metric-card"><span class="text-muted">Total Requests</span><strong>{{ $stats['requests'] }}</strong></div></div>
    <div class="col-md-3 col-6"><div class="metric-card"><span class="text-muted">Pending</span><strong>{{ $stats['pending'] }}</strong></div></div>
    <div class="col-md-3 col-6"><div class="metric-card"><span class="text-muted">Released</span><strong>{{ $stats['released'] }}</strong></div></div>
    <div class="col-md-3 col-6"><div class="metric-card"><span class="text-muted">Activities Joined</span><strong>{{ $stats['events'] }}</strong></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="portal-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Requests</h5>
                <a href="{{ route('resident-portal.requests') }}" class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Request #</th><th>Document</th><th>Status</th><th>Payment</th></tr></thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td>{{ $request->request_number }}</td>
                                <td>{{ $request->documentType->name ?? 'Document' }}</td>
                                <td><span class="badge text-bg-secondary">{{ $request->status }}</span></td>
                                <td>{{ $request->payment ? 'Paid' : ($request->documentType->fee > 0 ? 'Unpaid' : 'No fee') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No document requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="portal-card p-4">
            <h5 class="mb-3">Upcoming Barangay Activities</h5>
            @forelse($events as $event)
                <div class="border-bottom pb-3 mb-3">
                    <strong>{{ $event->title }}</strong>
                    <div class="small text-muted">{{ $event->event_date->format('M d, Y') }} | {{ $event->location }}</div>
                </div>
            @empty
                <p class="text-muted mb-0">No upcoming activities posted.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
