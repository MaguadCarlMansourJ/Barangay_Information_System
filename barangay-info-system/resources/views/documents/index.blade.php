@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Document Requests</h3>
        <a href="{{ route('documents.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Request</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <small class="text-muted">Total Revenue from Documents</small>
                    <h3 class="mb-0">PHP {{ number_format($paymentStats['total_revenue'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <small class="text-muted">Paid Requests</small>
                    <h3 class="mb-0">{{ number_format($paymentStats['paid_requests']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body">
                    <small class="text-muted">Approved / Ready Unpaid</small>
                    <h3 class="mb-0">{{ number_format($paymentStats['unpaid_requests']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Resident</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Fee</th>
                            <th>Date Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                        <tr>
                            <td>{{ $doc->request_number }}</td>
                            <td>{{ $doc->resident->full_name ?? 'N/A' }}</td>
                            <td>{{ $doc->documentType->name }}</td>
                            <td>
                                <span class="badge bg-{{ $doc->status == 'Pending' ? 'warning' : ($doc->status == 'Approved' ? 'info' : ($doc->status == 'Released' ? 'success' : 'secondary')) }}">
                                    {{ $doc->status }}
                                </span>
                            </td>
                            <td>
                                @if($doc->payment)
                                    <span class="badge bg-success">Paid</span>
                                @elseif($doc->documentType->fee > 0)
                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                @else
                                    <span class="badge bg-secondary">No fee</span>
                                @endif
                            </td>
                            <td>PHP {{ number_format($doc->documentType->fee, 2) }}</td>
                            <td>{{ $doc->date_requested->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('documents.show', $doc) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('documents.edit', $doc) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                @if(in_array($doc->status, ['Approved', 'Ready']) && $doc->documentType->fee > 0 && ! $doc->payment)
                                    <a href="{{ route('payments.create', ['document_request_id' => $doc->id]) }}" class="btn btn-sm btn-success" title="Record Payment">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                @endif
                                @if($doc->payment)
                                    <a href="{{ route('payments.receipt', $doc->payment) }}" class="btn btn-sm btn-outline-success" title="View Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No document requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $documents->links() }}
        </div>
</div>
@endsection
