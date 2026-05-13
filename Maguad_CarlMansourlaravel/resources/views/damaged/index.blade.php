@extends('layouts.app')

@section('title', 'Damaged Goods')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Damaged Goods</h2>
        <p class="page-subtitle">Track damaged inventory</p>
    </div>
    <a href="{{ route('damaged.create') }}" class="btn btn-danger">
        <i class="fas fa-plus"></i> Record Damage
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($damagedGoods as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td><span class="status-badge danger">{{ $item->quantity }}</span></td>
                        <td>{{ $item->reason }}</td>
                        <td>{{ $item->date->format('M d, Y') }}</td>
                        <td class="actions">
                            <form action="{{ route('damaged.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this damaged goods record?')">
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
                        <td colspan="5" style="text-align:center;color:var(--text-secondary);padding:40px;">
                            <i class="fas fa-times-circle" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                            No damaged goods records. <a href="{{ route('damaged.create') }}" style="color:var(--accent);">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $damagedGoods->links() }}
        </div>
    </div>
</div>
@endsection

