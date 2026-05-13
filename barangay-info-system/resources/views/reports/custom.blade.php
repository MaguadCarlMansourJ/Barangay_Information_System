@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Custom Report Builder</h3>
            <p class="text-muted mb-0">Generate tailored reports with specific date ranges and data filters.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <form method="GET" action="{{ route('reports.custom') }}" id="customReportForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Report Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select Report Type</option>
                                    <option value="population">Population Summary</option>
                                    <option value="financial">Financial Summary</option>
                                    <option value="documents">Document Requests</option>
                                    <option value="blotters">Blotter Cases</option>
                                    <option value="events">Events & Attendance</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-top">
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Generate Preview
                                    </button>
                                </div>
                                @if(request()->filled('type'))
                                <div class="col-md-3">
                                    <form method="POST" action="{{ route('reports.export') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ request('type') }}">
                                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-download me-2"></i>Export PDF
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="POST" action="{{ route('reports.export') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ request('type') }}excel">
                                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                        <button type="submit" class="btn btn-info w-100">
                                            <i class="fas fa-file-excel me-2"></i>Export Excel
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0"><i class="fas fa-info-circle text-info me-2"></i>Quick Preview</h6>
                </div>
                <div class="card-body">
                    @if(request()->filled('type'))
                    <div class="alert alert-info">
                        <h6>{{ ucfirst(request('type')) }} Report Preview</h6>
                        <p class="mb-1"><strong>Date Range:</strong> {{ request('date_from', 'All time') }} to {{ request('date_to', 'All time') }}</p>
                        <p class="mb-0"><strong>Records found:</strong> <span class="badge bg-primary">{{ $recordCount ?? 0 }}</span></p>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Configure report parameters on the left to see preview</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

