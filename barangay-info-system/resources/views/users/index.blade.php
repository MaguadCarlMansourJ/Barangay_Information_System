@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">User Management</h2>
                    <p class="text-muted mb-0">Manage system users and permissions</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Add New User
                    </a>
                    <button class="btn btn-outline-secondary" onclick="exportUsers()" title="Export Users">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users text-primary fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                    <p class="text-muted mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check text-success fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $activeUsers }}</h3>
                    <p class="text-muted mb-0">Active Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-times text-warning fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $inactiveUsers }}</h3>
                    <p class="text-muted mb-0">Inactive Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-shield text-info fa-2x mb-2"></i>
                    <h3 class="mb-0">{{ $rolesCount }}</h3>
                    <p class="text-muted mb-0">Roles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-header bg-transparent">
            <h6 class="mb-0">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Search Name/Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Enter name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Role</label>
                    <select class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-outline-primary flex-fill" onclick="applyUserFilters()">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h6 class="mb-0">System Users ({{ $users->total() }})</h6>
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('table')">
                    <i class="fas fa-table"></i> Table
                </button>
                <button class="btn btn-sm btn-outline-secondary active" onclick="toggleView('grid')">
                    <i class="fas fa-th"></i> Grid
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Table View (Default) -->
            <div id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="avatar avatar-xs">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32&background=6c757d&color=fff" class="rounded-circle" alt="{{ $user->name }}">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $user->email }}</div>
                                </td>
                                <th>
                                    <span class="badge bg-label-{{ $user->role == 'Captain' ? 'primary' : ($user->role == 'Treasurer' ? 'warning' : 'info') }}">
                                        {{ $user->role }}
                                    </span>
                                </th>
                                <td>
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary" title="Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline" style="margin-left: -6px;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete {{ $user->name }}?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-4"></i>
                                    <h5 class="text-muted mb-2">No users found</h5>
                                    <p class="text-muted">No users match your criteria.</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-primary">Show All Users</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            @foreach ($users->appends(request()->all())->getUrlRange(1, $users->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
let currentView = 'table';

function toggleView(view) {
    currentView = view;
    document.querySelectorAll('.btn-group-sm button').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (view === 'table') {
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('gridView').style.display = 'none';
    } else {
        document.getElementById('tableView').style.display = 'none';
        document.getElementById('gridView').style.display = 'block';
    }
}

function applyUserFilters() {
    const params = new URLSearchParams();
    const search = document.getElementById('searchInput').value.trim();
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    if (search) params.append('search', search);
    if (role) params.append('role', role);
    if (status !== '') params.append('active', status);
    
    window.location.search = params.toString();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    window.location.href = '{{ route("users.index") }}';
}

function exportUsers() {
    window.location.href = '{{ route("users.export") }}?' + new URLSearchParams(window.location.search).toString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Enter key search
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyUserFilters();
    });
});
</script>
@endpush

@endsection
