@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-value">{{ $lowStocks }}</div>
        <div class="stat-label">Low Stock Items</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-truck"></i></div>
        <div class="stat-value">{{ $totalSuppliers }}</div>
        <div class="stat-label">Suppliers</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ $totalEmployees }}</div>
        <div class="stat-label">Employees</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-arrow-down" style="color:var(--success);margin-right:8px;"></i> Recent Stock In</div>
        <a href="{{ route('stock-ins.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStockIns as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td>{{ $item->supplier->name }}</td>
                        <td><span class="status-badge success">+{{ $item->quantity }}</span></td>
                        <td>${{ number_format($item->unit_cost, 2) }}</td>
                        <td>{{ $item->date->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-secondary);">No stock in records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-arrow-up" style="color:var(--warning);margin-right:8px;"></i> Recent Stock Out</div>
        <a href="{{ route('stock-outs.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Employee</th>
                        <th>Quantity</th>
                        <th>Department</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStockOuts as $item)
                    <tr>
                        <td><strong>{{ $item->product->name }}</strong></td>
                        <td>{{ $item->employee->name }}</td>
                        <td><span class="status-badge warning">-{{ $item->quantity }}</span></td>
                        <td>{{ $item->department }}</td>
                        <td>{{ $item->date->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-secondary);">No stock out records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

