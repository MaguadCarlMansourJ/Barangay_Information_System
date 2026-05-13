@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Barangay Health Center - Visits</h2>
            <p class="text-muted mb-0">Manage clinic consultations and related records.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('health-visits.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Visit
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('health-visits.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search Resident</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="e.g., Juan Cruz">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Service Type</label>
                    <select name="service_type" class="form-select">
                        <option value="">All</option>
                        @foreach($serviceTypes as $value)
                            <option value="{{ $value }}" {{ request('service_type') === $value ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        @foreach($statuses as $value)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('health-visits.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Visits List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Visit #</th>
                            <th>Resident</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($visits as $visit)
                            <tr>
                                <td class="fw-semibold">{{ $visit->visit_number }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $visit->resident->full_name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $visit->resident->contact_number ?? '' }}</small>
                                </td>
                                <td>{{ $visit->service_type }}</td>
                                <td>{{ optional($visit->visit_date)->format('M d, Y') }}</td>
                                <td>{{ $visit->visit_time }}</td>
                                <td>
                                    @php
                                        $badge = match($visit->status) {
                                            'Done' => 'success',
                                            'Scheduled' => 'primary',
                                            'Cancelled' => 'secondary',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $visit->status }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('health-visits.show', $visit) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('health-visits.edit', $visit) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('health-visits.destroy', $visit) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this visit?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No health visits found</h5>
                                    <p class="text-muted">Start by adding a barangay clinic record.</p>
                                    <a href="{{ route('health-visits.create') }}" class="btn btn-primary">Add Visit</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $visits->firstItem() ?? 0 }} to {{ $visits->lastItem() ?? 0 }} of {{ $visits->total() }} visits
                </div>
                <ul class="pagination mb-0">
                    @foreach ($visits->appends(request()->query())->getUrlRange(1, $visits->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $visits->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

