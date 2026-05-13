@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Residents</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_residents']) }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-mars me-1"></i> {{ $stats['total_males'] }} Males &nbsp;
                            <i class="fas fa-venus ms-2 me-1"></i> {{ $stats['total_females'] }} Females
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Requests</h6>
                            <h2 class="mb-0">{{ number_format($stats['pending_requests']) }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-file-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Active Blotters</h6>
                            <h2 class="mb-0">{{ number_format($stats['active_blotters']) }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-gavel fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Revenue</h6>
                            <h2 class="mb-0">PHP {{ number_format($stats['total_revenue'], 2) }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Requests --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Document Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Resident</th>
                                    <th>Document Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>{{ $request->request_number }}</td>
                                    <td>{{ $request->resident->full_name ?? 'N/A' }}</td>
                                    <td>{{ $request->documentType->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $request->status == 'Pending' ? 'warning' : 
                                            ($request->status == 'Approved' ? 'info' : 
                                            ($request->status == 'Released' ? 'success' : 'secondary'))
                                        }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request->date_requested->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @foreach($upcomingEvents as $event)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $event->event_date->format('M d, Y') }}
                                    <i class="fas fa-clock ms-2 me-1"></i> {{ date('g:i A', strtotime($event->start_time)) }}
                                </small>
                                <p class="small text-muted mt-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $event->location }}</p>
                            </div>
                            <span class="badge bg-primary">{{ $event->status }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Population by Purok Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Population by Purok</h5>
                </div>
                <div class="card-body">
                    <canvas id="populationChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Blotters --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Blotter Cases</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentBlotters as $blotter)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $blotter->blotter_number }}</strong>
                                    <p class="mb-0 small">{{ Str::limit($blotter->description, 50) }}</p>
                                </div>
                                <span class="badge bg-{{ 
                                    $blotter->status == 'Open' ? 'danger' : 
                                    ($blotter->status == 'Under Investigation' ? 'warning' : 
                                    ($blotter->status == 'Resolved' ? 'success' : 'secondary'))
                                }}">
                                    {{ $blotter->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Population Chart
    const ctx = document.getElementById('populationChart').getContext('2d');
    const populationData = @json($populationByPurok);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: populationData.map(item => item.name),
            datasets: [{
                label: 'Number of Residents',
                data: populationData.map(item => item.total),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
