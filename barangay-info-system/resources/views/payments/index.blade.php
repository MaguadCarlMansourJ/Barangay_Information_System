@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Payments</h2>
            <p class="text-muted mb-0">Payment records and collection summary</p>
        </div>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Record Payment
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Revenue</h6>
                            <h2 class="mb-0">PHP {{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Payment List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>OR Number</th>
                            <th>Request #</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Received By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="fw-semibold">{{ $payment->or_number }}</td>
                                <td>{{ $payment->documentRequest->request_number ?? 'N/A' }}</td>
                                <td>PHP {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-receipt"></i></a>
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No payments recorded</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments
                </div>
                <ul class="pagination mb-0">
                    @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $payments->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
