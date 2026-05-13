@extends('layouts.app')

@section('title', 'Add Supplier')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Add Supplier</h2>
    <p class="page-subtitle">Add a new coffee supplier</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Supplier Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required placeholder="Phone or email">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

