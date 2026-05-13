@extends('layouts.app')

@section('title', 'Add Stock In')

@section('content')
<div class="mb-4">
    <h2 class="page-title">Add Stock In</h2>
    <p class="page-subtitle">Record incoming inventory</p>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('stock-ins.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" class="form-control" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Received By</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Unit Cost (₱)</label>
                <input type="number" name="unit_cost" class="form-control" value="{{ old('unit_cost', 0) }}" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Save Stock In
                </button>
                <a href="{{ route('stock-ins.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

