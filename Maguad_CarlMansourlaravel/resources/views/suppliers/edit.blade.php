@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Edit Supplier</h2>
    <p class="page-subtitle">Update supplier details</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Supplier Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $supplier->contact) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Supplier
                </button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

