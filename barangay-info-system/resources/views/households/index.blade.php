@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Households</h2>
            <p class="text-muted mb-0">{{ $households->total() }} total households</p>
        </div>
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Household
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('households.index') }}">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="House number or address">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Purok</label>
                    <select name="purok" class="form-select">
                        <option value="">All Puroks</option>
                        @foreach($puroks as $purok)
                            <option value="{{ $purok->id }}" {{ request('purok') == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('households.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Household List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>House No.</th>
                            <th>Address</th>
                            <th>Purok</th>
                            <th>Members</th>
                            <th style="width: 170px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($households as $household)
                            <tr>
                                <td class="fw-semibold">{{ $household->house_number }}</td>
                                <td>{{ $household->address }}</td>
                                <td><span class="badge bg-primary">{{ $household->purok->name ?? 'N/A' }}</span></td>
                                <td>{{ $household->residents->count() }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('households.show', $household) }}" class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('households.edit', $household) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('households.destroy', $household) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this household?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No households found</h5>
                                    <a href="{{ route('households.create') }}" class="btn btn-primary mt-2">Add First Household</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $households->firstItem() ?? 0 }} to {{ $households->lastItem() ?? 0 }} of {{ $households->total() }} households
                </div>
                <ul class="pagination mb-0">
                    @foreach ($households->appends(request()->query())->getUrlRange(1, $households->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $households->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
