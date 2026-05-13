@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Household Profile</h2>
            <p class="text-muted mb-0">Household information and linked residents</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('residents.create', ['household_id' => $household->id]) }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i>Add Resident
            </a>
            <a href="{{ route('households.edit', $household) }}" class="btn btn-outline-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('households.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-home fa-4x text-primary mb-3"></i>
                        <h4 class="mb-1">House {{ $household->house_number }}</h4>
                        <span class="badge bg-primary">{{ $household->purok->name ?? 'N/A' }}</span>
                    </div>
                    <div class="border-top pt-3">
                        <p class="mb-2"><strong>Address:</strong> {{ $household->address }}</p>
                        <p class="mb-2"><strong>Members:</strong> {{ $household->residents->count() }}</p>
                        <p class="mb-0"><strong>Created:</strong> {{ $household->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Residents</h5>
                    <span class="badge bg-secondary">{{ $household->residents->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Age / Gender</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th style="width: 90px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($household->residents as $resident)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $resident->full_name }}</div>
                                            <small class="text-muted">{{ $resident->civil_status }}</small>
                                        </td>
                                        <td>{{ $resident->age }} / {{ $resident->gender }}</td>
                                        <td>{{ $resident->contact_number ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $resident->is_active ? 'success' : 'danger' }}">
                                                {{ $resident->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('residents.show', $resident) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No residents linked to this household</h5>
                                            <a href="{{ route('residents.create', ['household_id' => $household->id]) }}" class="btn btn-primary mt-2">Add Resident</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
