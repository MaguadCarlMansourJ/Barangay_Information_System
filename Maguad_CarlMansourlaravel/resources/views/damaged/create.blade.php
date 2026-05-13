@extends('layouts.app')

@section('title', 'Record Damaged Goods')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Record Damaged Goods</h2>
    <p class="page-subtitle">Log damaged inventory</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('damaged.store') }}">
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
                <label class="form-label">Quantity Damaged</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" required placeholder="e.g. Spilled, Expired, Broken">
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-save"></i> Record Damage
                </button>
                <a href="{{ route('damaged.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

