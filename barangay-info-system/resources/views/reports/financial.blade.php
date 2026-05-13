@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Financial Report</h2>
            <p class="text-muted mb-0">Barangay collections, payment methods, and revenue summary</p>
        </div>
        <form method="POST" action="{{ route('reports.export') }}">
            @csrf
            <input type="hidden" name="type" value="financial">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download me-1"></i>Export CSV
            </button>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Revenue</h6>
                    <h2 class="mb-0">PHP {{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Payments</h6>
                    <h2 class="mb-0">{{ number_format($totalPayments) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Average Payment</h6>
                    <h2 class="mb-0">PHP {{ number_format($averagePayment, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Today</h6>
                    <h2 class="mb-0">PHP {{ number_format($todayRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Revenue by Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Method</th>
                                    <th>Payments</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($methodTotals as $method)
                                    <tr>
                                        <td>{{ $method->payment_method }}</td>
                                        <td>{{ number_format($method->total_payments) }}</td>
                                        <td>PHP {{ number_format($method->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No payment method data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Monthly Revenue</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Payments</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyTotals as $month)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}</td>
                                        <td>{{ number_format($month->total_payments) }}</td>
                                        <td>PHP {{ number_format($month->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No monthly revenue data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Payment Records</h5>
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
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="fw-semibold">{{ $payment->or_number }}</td>
                                <td>{{ $payment->documentRequest->request_number ?? 'N/A' }}</td>
                                <td>PHP {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">No payments recorded.</td>
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
