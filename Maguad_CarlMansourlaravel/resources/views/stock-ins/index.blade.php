@extends('layouts.app')

@section('title', 'Stock In')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Stock In</h2>
        <p class="page-subtitle">Track incoming inventory</p>
    </div>
    <a href="{{ route('stock-ins.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add Stock
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>Employee</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockIns as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td>{{ $item->supplier->name }}</td>
                        <td>{{ $item->employee->name }}</td>
                        <td><span class="status-badge success">+{{ $item->quantity }}</span></td>
                        <td>${{ number_format($item->unit_cost, 2) }}</td>
                        <td><strong>${{ number_format($item->quantity * $item->unit_cost, 2) }}</strong></td>
                        <td>{{ $item->date->format('M d, Y') }}</td>
                        <td class="actions">
                            <form action="{{ route('stock-ins.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this stock in record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--text-secondary);padding:40px;">
                            <i class="fas fa-arrow-down" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                            No stock in records. <a href="{{ route('stock-ins.create') }}" style="color:var(--accent);">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $stockIns->links() }}
        </div>
    </div>
</div>
@endsection

