@extends('layouts.app')

@section('title', 'Stock Out')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Stock Out</h2>
        <p class="page-subtitle">Track outgoing inventory</p>
    </div>
    <a href="{{ route('stock-outs.create') }}" class="btn btn-warning">
        <i class="fas fa-plus"></i> Deduct Stock
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOuts as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td>{{ $item->employee->name }}</td>
                        <td>{{ $item->department }}</td>
                        <td><span class="status-badge warning">-{{ $item->quantity }}</span></td>
                        <td>{{ $item->date->format('M d, Y') }}</td>
                        <td class="actions">
                            <form action="{{ route('stock-outs.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this stock out record?')">
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
                        <td colspan="6" style="text-align:center;color:var(--text-secondary);padding:40px;">
                            <i class="fas fa-arrow-up" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                            No stock out records. <a href="{{ route('stock-outs.create') }}" style="color:var(--accent);">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $stockOuts->links() }}
        </div>
    </div>
</div>
@endsection

