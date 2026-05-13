@extends('layouts.app')

@section('title', 'Add Stock Out')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Add Stock Out</h2>
    <p class="page-subtitle">Record outgoing inventory</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('stock-outs.store') }}">
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
                <label class="form-label">Employee</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control" value="{{ old('department') }}" required placeholder="e.g. Kitchen, Storage">
            </div>
            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Save Stock Out
                </button>
                <a href="{{ route('stock-outs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

