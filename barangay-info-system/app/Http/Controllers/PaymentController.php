<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\DocumentRequest;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['documentRequest.resident', 'receivedBy'])->latest()->paginate(15);
        $totalRevenue = Payment::sum('amount');

        return view('payments.index', compact('payments', 'totalRevenue'));
    }

    public function create(Request $request)
    {
        $document = null;
        if ($request->filled('document_request_id')) {
            $document = DocumentRequest::with(['resident', 'documentType', 'payment'])->findOrFail($request->document_request_id);

            if ($document->payment) {
                return redirect()->route('payments.receipt', $document->payment)->with('error', 'This document request already has a recorded payment.');
            }

            if (! in_array($document->status, ['Approved', 'Ready'])) {
                return redirect()->route('documents.show', $document)->with('error', 'Only approved or ready document requests can be paid.');
            }
        }

        $documents = DocumentRequest::with(['resident', 'documentType'])
            ->whereIn('status', ['Approved', 'Ready'])
            ->whereDoesntHave('payment')
            ->latest()
            ->get();
        $totalRevenue = Payment::sum('amount');
        $todayRevenue = Payment::whereDate('payment_date', today())->sum('amount');

        return view('payments.create', compact('document', 'documents', 'totalRevenue', 'todayRevenue'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_request_id' => 'required|exists:document_requests,id',
            'or_number' => 'required|string|unique:payments',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,GCash,PayMaya',
            'reference_number' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        $document = DocumentRequest::with(['documentType', 'payment'])->findOrFail($validated['document_request_id']);

        if ($document->payment) {
            return back()->withInput()->with('error', 'This document request already has a payment record.');
        }

        if (! in_array($document->status, ['Approved', 'Ready'])) {
            return back()->withInput()->with('error', 'Only approved or ready document requests can be paid.');
        }

        $validated['received_by'] = auth()->id();

        $payment = Payment::create($validated);
        $payment->documentRequest()->update(['status' => 'Ready']);

        return redirect()->route('payments.receipt', $payment)->with('success', 'Payment recorded successfully');
    }

    public function processPayment(Request $request, DocumentRequest $document)
    {
        return redirect()->route('payments.create', ['document_request_id' => $document->id]);
    }

    public function generateReceipt(Payment $payment)
    {
        $payment->load(['documentRequest.resident', 'documentRequest.documentType', 'receivedBy']);
        return view('payments.receipt', compact('payment'));
    }

    public function show(Payment $payment)
    {
        return $this->generateReceipt($payment);
    }

    public function edit(Payment $payment)
    {
        $payment->load(['documentRequest.resident', 'documentRequest.documentType']);
        $document = $payment->documentRequest;
        $documents = collect([$document]);
        $totalRevenue = Payment::sum('amount');
        $todayRevenue = Payment::whereDate('payment_date', today())->sum('amount');

        return view('payments.create', compact('payment', 'document', 'documents', 'totalRevenue', 'todayRevenue'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'document_request_id' => 'required|exists:document_requests,id',
            'or_number' => 'required|string|unique:payments,or_number,' . $payment->id,
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,GCash,PayMaya',
            'reference_number' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.receipt', $payment)->with('success', 'Payment updated successfully');
    }

    public function destroy(Payment $payment)
    {
        $document = $payment->documentRequest;
        $payment->delete();

        if ($document && $document->status === 'Ready') {
            $document->update(['status' => 'Approved']);
        }

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully');
    }
}
