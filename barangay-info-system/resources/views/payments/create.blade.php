@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @php($isEdit = isset($payment))

    <h3 class="mb-4">{{ $isEdit ? 'Edit Payment' : 'Record Payment' }}</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <small class="text-muted">Current Total Revenue</small>
                    <h3 class="mb-0">PHP {{ number_format($totalRevenue ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <small class="text-muted">Today's Collection</small>
                    <h3 class="mb-0">PHP {{ number_format($todayRevenue ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($documents->isEmpty() && ! $isEdit)
                <div class="alert alert-info mb-0">
                    No approved or ready unpaid document requests are available for payment.
                    Approve a document request first, then record its payment here.
                </div>
            @else
            <form action="{{ $isEdit ? route('payments.update', $payment) : route('payments.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Document Request</label>
                    <select name="document_request_id" id="documentRequestSelect" class="form-control" required>
                        @foreach($documents as $item)
                            <option value="{{ $item->id }}" data-fee="{{ $item->documentType->fee ?? 0 }}" {{ old('document_request_id', $document->id ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->request_number }} - {{ $item->resident->full_name ?? 'N/A' }} - {{ $item->documentType->name ?? 'Document' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">OR Number</label>
                    <input type="text" name="or_number" class="form-control" value="{{ old('or_number', $payment->or_number ?? 'OR-' . now()->format('Ymd') . '-') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" id="paymentAmount" step="0.01" min="0" class="form-control" value="{{ old('amount', $payment->amount ?? optional(optional($document)->documentType)->fee) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        @foreach(['Cash', 'GCash', 'PayMaya'] as $method)
                            <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method ?? 'Cash') == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reference Number</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $payment->reference_number ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', isset($payment) ? $payment->payment_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Payment' : 'Record Payment' }}</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('documentRequestSelect');
    const amount = document.getElementById('paymentAmount');

    if (!select || !amount) return;

    function syncAmountFromDocument() {
        const selected = select.options[select.selectedIndex];
        if (!selected) return;

        const fee = selected.dataset.fee;
        if (fee !== undefined && amount.value === '') {
            amount.value = Number(fee).toFixed(2);
        }
    }

    select.addEventListener('change', function () {
        const selected = select.options[select.selectedIndex];
        amount.value = Number(selected.dataset.fee || 0).toFixed(2);
    });

    syncAmountFromDocument();
});
</script>
@endpush
