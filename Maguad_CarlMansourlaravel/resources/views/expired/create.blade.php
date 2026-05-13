@extends('layouts.app')

@section('title', 'Record Expired Goods')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Record Expired Goods</h2>
    <p class="page-subtitle">Log expired inventory</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('expired.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} (Stock: {{ $product->quantity }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Quantity Expired</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiration Date</label>
                <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date', date('Y-m-d')) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save"></i> Record Expired
                </button>
                <a href="{{ route('expired.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

