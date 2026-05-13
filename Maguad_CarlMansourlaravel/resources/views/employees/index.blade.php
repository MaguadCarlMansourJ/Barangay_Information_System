@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Employees</h2>
        <p class="page-subtitle">Manage your staff</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Employee
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td><strong>{{ $employee->name }}</strong></td>
                        <td><span class="status-badge primary">{{ $employee->role }}</span></td>
                        <td class="actions">
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this employee?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:var(--text-secondary);padding:40px;">
                            <i class="fas fa-users" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                            No employees found. <a href="{{ route('employees.create') }}" style="color:var(--accent);">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection

