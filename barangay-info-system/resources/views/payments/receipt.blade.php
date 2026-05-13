@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Payment Receipt</h3>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center border-bottom pb-3 mb-4">
                <h5 class="mb-1">Republic of the Philippines</h5>
                <h4 class="mb-1">{{ config('app.name') }}</h4>
                <p class="mb-0">Official Barangay Collection Receipt</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>OR Number:</strong> {{ $payment->or_number }}</p>
                    <p><strong>Request #:</strong> {{ $payment->documentRequest->request_number ?? 'N/A' }}</p>
                    <p><strong>Resident:</strong> {{ $payment->documentRequest->resident->full_name ?? 'N/A' }}</p>
                    <p><strong>Document:</strong> {{ $payment->documentRequest->documentType->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Amount:</strong> PHP {{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
                    <p><strong>Reference Number:</strong> {{ $payment->reference_number ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $payment->payment_date->format('M d, Y') }}</p>
                    <p><strong>Received By:</strong> {{ $payment->receivedBy->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="mt-5 text-end">
                <div class="d-inline-block text-center">
                    <div style="border-top: 1px solid #333; min-width: 240px;"></div>
                    <strong>Authorized Collecting Officer</strong>
                </div>
            </div>

            <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-4">Back</a>
        </div>
    </div>
</div>
@endsection
