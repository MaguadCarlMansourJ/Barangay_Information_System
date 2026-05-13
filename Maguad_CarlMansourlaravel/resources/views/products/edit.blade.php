@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Edit Product</h2>
    <p class="page-subtitle">Update product details</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Beans" {{ old('category', $product->category) == 'Beans' ? 'selected' : '' }}>Coffee Beans</option>
                    <option value="Ground" {{ old('category', $product->category) == 'Ground' ? 'selected' : '' }}>Ground Coffee</option>
                    <option value="Instant" {{ old('category', $product->category) == 'Instant' ? 'selected' : '' }}>Instant Coffee</option>
                    <option value="Equipment" {{ old('category', $product->category) == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="Packaging" {{ old('category', $product->category) == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="Other" {{ old('category', $product->category) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Minimum Stock Level</label>
                <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}" min="0" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

