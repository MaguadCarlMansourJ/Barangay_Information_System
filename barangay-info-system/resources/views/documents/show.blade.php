@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Document Request Details</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <p><strong>Request Number:</strong> {{ $document->request_number }}</p>
                    <p><strong>Resident:</strong> {{ $document->resident->full_name ?? 'N/A' }}</p>
                    <p><strong>Document Type:</strong> {{ $document->documentType->name }}</p>
                    <p><strong>Fee:</strong> PHP {{ number_format($document->documentType->fee, 2) }}</p>
                    <p><strong>Status:</strong> {{ $document->status }}</p>
                    <p><strong>Purpose:</strong> {{ $document->purpose }}</p>
                    <p><strong>Remarks:</strong> {{ $document->remarks ?? 'N/A' }}</p>
                    <p><strong>Date Requested:</strong> {{ $document->date_requested->format('M d, Y') }}</p>
                    <p><strong>Date Ready:</strong> {{ $document->date_ready ? $document->date_ready->format('M d, Y') : 'N/A' }}</p>
                    <p><strong>Date Released:</strong> {{ $document->date_released ? $document->date_released->format('M d, Y') : 'N/A' }}</p>
                    <p><strong>Approved By:</strong> {{ $document->approvedBy->name ?? 'N/A' }}</p>
                    <p><strong>Released By:</strong> {{ $document->releasedBy->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Workflow Actions</h5>
                </div>
                <div class="card-body">
                    @if($document->status == 'Pending')
                        <form action="{{ route('documents.approve', $document) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Approve</button>
                        </form>
                        <form action="{{ route('documents.reject', $document) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Reject</button>
                        </form>
                    @endif

                    @if(in_array($document->status, ['Approved', 'Ready']))
                        @if($document->documentType->fee > 0 && ! $document->payment)
                            <a href="{{ route('payments.create', ['document_request_id' => $document->id]) }}" class="btn btn-primary w-100 mb-2">Record Payment</a>
                        @endif
                        <form action="{{ route('documents.release', $document) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">Release Document</button>
                        </form>
                    @endif

                    @if($document->status == 'Released')
                        <div class="alert alert-success mb-0">This document has been released.</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Payment</h5>
                </div>
                <div class="card-body">
                    @if($document->payment)
                        <p><strong>OR Number:</strong> {{ $document->payment->or_number }}</p>
                        <p><strong>Amount:</strong> PHP {{ number_format($document->payment->amount, 2) }}</p>
                        <a href="{{ route('payments.receipt', $document->payment) }}" class="btn btn-outline-primary w-100">View Receipt</a>
                    @else
                        <p class="text-muted mb-0">{{ $document->documentType->fee > 0 ? 'No payment recorded yet.' : 'This document has no fee.' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
