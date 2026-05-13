@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Residents Management</h2>
        <p class="text-muted mb-0">{{ $residents->total() }} total residents</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('residents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Resident
        </a>
        <button class="btn btn-outline-secondary" onclick="exportResidents()">
            <i class="fas fa-download me-1"></i>Export
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form id="residentsFilter" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search Name</label>
                <input type="text" class="form-control" id="searchName" value="{{ request('search') }}" placeholder="Enter name...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Purok</label>
                <select class="form-select" id="purokFilter">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                    <option value="{{ $purok->id }}" {{ request('purok') == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select class="form-select" id="genderFilter">
                    <option value="">All</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Age Range</label>
                <select class="form-select" id="ageFilter">
                    <option value="">All Ages</option>
                    {{-- Barangay election eligibility bins (PH standard) --}}
                    <option value="18+" {{ request('age') == '18+' ? 'selected' : '' }}>18+ (Regular voter / Brgy Captain & Kagawad)</option>
                    <option value="15-30" {{ request('age') == '15-30' ? 'selected' : '' }}>15–30 (SK voter)</option>
                    <option value="18-24" {{ request('age') == '18-24' ? 'selected' : '' }}>18–24 (SK Chairperson & SK Kagawad)</option>
                </select>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Residents Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Residents List</h5>
        <div class="d-flex gap-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="cardViewToggle" checked onchange="toggleView()">
                <label class="form-check-label" for="cardViewToggle">
                    Card View
                </label>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Table View -->
        <div id="tableView">
            <div class="table-responsive">
                <table class="table table-hover" id="residentsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Household</th>
                            <th>Purok</th>
                            <th>Age / Gender</th>
                            <th>Barangay Tags</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        <tr>
                            <td>
                                <div class="avatar avatar-sm">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->full_name) }}&size=40&background=667eea&color=fff" alt="{{ $resident->full_name }}" class="rounded-circle">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $resident->full_name }}</div>
                                    <small class="text-muted">{{ $resident->civil_status }}</small>
                                </div>
                            </td>
                            <td>
                                <div>{{ $resident->household->house_number ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $resident->household->head_name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $resident->household->purok->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>{{ $resident->age }} yrs</div>
                                <span class="badge bg-{{ $resident->gender == 'Male' ? 'info' : 'pink' }}">{{ $resident->gender[0] }}</span>
                            </td>
                            <td>
                                @if($resident->is_registered_voter)
                                    <span class="badge bg-primary">Voter</span>
                                @endif
                                @if($resident->is_senior_citizen)
                                    <span class="badge bg-success">Senior</span>
                                @endif
                                @if($resident->is_pwd)
                                    <span class="badge bg-warning text-dark">PWD</span>
                                @endif
                                @if($resident->is_solo_parent)
                                    <span class="badge bg-info text-dark">Solo Parent</span>
                                @endif
                                @if($resident->is_4ps_beneficiary)
                                    <span class="badge bg-secondary">4Ps</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-phone me-1"></i>{{ $resident->contact_number ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $resident->is_active ? 'success' : 'danger' }}">
                                    {{ $resident->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('residents.edit', $resident) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('residents.destroy', $resident) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete {{ $resident->full_name }}?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No residents found</h5>
                                <p class="text-muted">Get started by adding your first resident.</p>
                                <a href="{{ route('residents.create') }}" class="btn btn-primary">Add First Resident</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $residents->firstItem() ?? 0 }} to {{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }} residents
                </div>
                <div>
                    <ul class="pagination mb-0">
                        @foreach ($residents->appends(request()->query())->getUrlRange(1, $residents->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $residents->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div id="cardView" style="display: none;">
            <div class="row g-4">
                @forelse($residents as $resident)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-lg me-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->full_name) }}&size=60&background=667eea&color=fff" alt="{{ $resident->full_name }}" class="rounded-circle">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $resident->full_name }}</h6>
                                    <small class="text-muted">{{ $resident->household->purok->name ?? 'N/A' }}</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Household</small>
                                <span class="fw-semibold">{{ $resident->household->house_number ?? 'N/A' }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Age / Gender</small>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">{{ $resident->age }} yrs</span>
                                    <span class="badge bg-info">{{ $resident->gender[0] }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Barangay Tags</small>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($resident->is_registered_voter)<span class="badge bg-primary">Voter</span>@endif
                                    @if($resident->is_senior_citizen)<span class="badge bg-success">Senior</span>@endif
                                    @if($resident->is_pwd)<span class="badge bg-warning text-dark">PWD</span>@endif
                                    @if($resident->is_solo_parent)<span class="badge bg-info text-dark">Solo Parent</span>@endif
                                    @if($resident->is_4ps_beneficiary)<span class="badge bg-secondary">4Ps</span>@endif
                                    @if(! $resident->is_registered_voter && ! $resident->is_senior_citizen && ! $resident->is_pwd && ! $resident->is_solo_parent && ! $resident->is_4ps_beneficiary)
                                        <span class="text-muted">None</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Contact</small>
                                <span>{{ $resident->contact_number ?? 'No phone' }}</span>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('residents.edit', $resident) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('residents.destroy', $resident) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <span class="badge bg-{{ $resident->is_active ? 'success' : 'danger' }} float-end">
                                {{ $resident->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted mb-2">No residents found</h4>
                        <p class="text-muted mb-4">Start by adding residents to your database.</p>
                        <a href="{{ route('residents.create') }}" class="btn btn-primary btn-lg">Add First Resident</a>
                    </div>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-4">
                <ul class="pagination justify-content-center mb-0">
                    @foreach ($residents->appends(request()->query())->getUrlRange(1, $residents->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $residents->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script>
function toggleView() {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    if (document.getElementById('cardViewToggle').checked) {
        tableView.style.display = 'none';
        cardView.style.display = 'block';
    } else {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
    }
}
function applyFilters() {
    const params = new window.URLSearchParams();
    const name = document.getElementById('searchName').value;
    const purok = document.getElementById('purokFilter').value;
    const gender = document.getElementById('genderFilter').value;
    const age = document.getElementById('ageFilter').value;
    
    if (name) params.append('search', name);
    if (purok) params.append('purok', purok);
    if (gender) params.append('gender', gender);
    if (age) params.append('age', age);

    window.location.href = '?'+params.toString();
}

function exportResidents() {
    window.location.href = '{{ route("residents.export") }}';
}

// Auto-trigger search on enter
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchName').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});
</script>
@endpush
@endsection
