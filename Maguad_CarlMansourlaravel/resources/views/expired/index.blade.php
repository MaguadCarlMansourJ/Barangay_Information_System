@extends('layouts.app')

@section('title', 'Expired Goods')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">Expired Goods</h2>
        <p class="page-subtitle">Track expired inventory</p>
    </div>
    <a href="{{ route('expired.create') }}" class="btn btn-danger">
        <i class="fas fa-plus"></i> Record Expired
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
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiredGoods as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td><span class="status-badge danger">{{ $item->quantity }}</span></td>
                        <td>{{ $item->expiration_date->format('M d, Y') }}</td>
                        <td class="actions">
                            <form action="{{ route('expired.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expired goods record?')">
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
                        <td colspan="4" style="text-align:center;color:var(--text-secondary);padding:40px;">
                            <i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                            No expired goods records. <a href="{{ route('expired.create') }}" style="color:var(--accent);">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $expiredGoods->links() }}
        </div>
    </div>
</div>
@endsection

