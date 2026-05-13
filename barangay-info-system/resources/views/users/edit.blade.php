@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Edit User</h3>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="Captain" {{ $user->role == 'Captain' ? 'selected' : '' }}>Captain</option>
                        <option value="Secretary" {{ $user->role == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="Treasurer" {{ $user->role == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                        <option value="Staff" {{ $user->role == 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="Resident" {{ $user->role == 'Resident' ? 'selected' : '' }}>Resident</option>
                    </select>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
</div>
@endsection
