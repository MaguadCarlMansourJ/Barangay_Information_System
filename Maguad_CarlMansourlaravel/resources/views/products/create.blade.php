@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Add Product</h2>
    <p class="page-subtitle">Create a new product in your inventory</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Beans" {{ old('category') == 'Beans' ? 'selected' : '' }}>Coffee Beans</option>
                    <option value="Ground" {{ old('category') == 'Ground' ? 'selected' : '' }}>Ground Coffee</option>
                    <option value="Instant" {{ old('category') == 'Instant' ? 'selected' : '' }}>Instant Coffee</option>
                    <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="Packaging" {{ old('category') == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Initial Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Minimum Stock Level</label>
                <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 10) }}" min="0" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

