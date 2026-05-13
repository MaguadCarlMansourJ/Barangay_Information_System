@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Barangay Reports</h3>
            <p class="text-muted mb-0">Generate official barangay reports and statistics</p>
        </div>
        @role('Captain')
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i> Export Reports
            </button>
            <ul class="dropdown-menu">
<li>
                    <form method="POST" action="{{ route('reports.export') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="population">
                        <button type="submit" class="dropdown-item border-0 bg-transparent">
                            Population (PDF)
                        </button>
                    </form>
                </li>
                <li>
                    <form method="POST" action="{{ route('reports.export') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="financial">
                        <button type="submit" class="dropdown-item border-0 bg-transparent">
                            Financial (CSV)
                        </button>
                    </form>
                </li>
                <li>
                    <form method="POST" action="{{ route('reports.export') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="type" value="documents">
                        <button type="submit" class="dropdown-item border-0 bg-transparent">
                            Documents (PDF)
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endrole
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Population Report</h5>
                        <p class="text-muted">Detailed population statistics by purok, age group, and gender.</p>
                    </div>
                    <a href="{{ route('reports.population') }}" class="btn btn-outline-primary w-100 mt-auto">
                        <i class="fas fa-eye me-2"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-coins fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Financial Report</h5>
                        <p class="text-muted">Barangay collections, payments, and financial summary.</p>
                    </div>
                    <a href="{{ route('reports.financial') }}" class="btn btn-outline-success w-100 mt-auto">
                        <i class="fas fa-eye me-2"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-file-contract fa-2x text-info"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Document Requests</h5>
                        <p class="text-muted">Status of clearances, certificates, and other documents.</p>
                    </div>
                    <a href="{{ route('reports.documents') }}" class="btn btn-outline-info w-100 mt-auto">
                        <i class="fas fa-eye me-2"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-gavel fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Blotter Report</h5>
                        <p class="text-muted">Barangay blotter cases and resolution status.</p>
                    </div>
                    <a href="{{ route('reports.blotters') }}" class="btn btn-outline-warning w-100 mt-auto">
                        <i class="fas fa-eye me-2"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-calendar-check fa-2x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Events Report</h5>
                        <p class="text-muted">Community events, attendance, and participation.</p>
                    </div>
                    <a href="{{ route('reports.events') }}" class="btn btn-outline-danger w-100 mt-auto">
                        <i class="fas fa-eye me-2"></i> View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                            <i class="fas fa-file-pdf fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title fw-semibold">Custom Report</h5>
                        <p class="text-muted">Generate customized reports for specific needs.</p>
                    </div>
                    <button class="btn btn-outline-secondary w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#customReportModal">
                        <i class="fas fa-cog me-2"></i> Configure
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Report Modal -->
    <div class="modal fade" id="customReportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Custom Report Builder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Select report type and date range for custom generation.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Report Type</label>
                            <select class="form-select">
                                <option>Population Summary</option>
                                <option>Financial Summary</option>
                                <option>Document Status</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">From</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Generate PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
