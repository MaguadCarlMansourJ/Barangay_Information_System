@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Edit Employee</h2>
    <p class="page-subtitle">Update employee details</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="Admin" {{ old('role', $employee->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Manager" {{ old('role', $employee->role) == 'Manager' ? 'selected' : '' }}>Manager</option>
                    <option value="Barista" {{ old('role', $employee->role) == 'Barista' ? 'selected' : '' }}>Barista</option>
                    <option value="Cashier" {{ old('role', $employee->role) == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="Staff" {{ old('role', $employee->role) == 'Staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Employee
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

