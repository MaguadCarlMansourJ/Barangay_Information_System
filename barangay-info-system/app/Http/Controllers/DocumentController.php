<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Payment;
use App\Models\Resident;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = DocumentRequest::with(['resident', 'documentType', 'payment'])->latest()->paginate(15);
        $paymentStats = [
            'total_revenue' => Payment::sum('amount'),
            'paid_requests' => DocumentRequest::whereHas('payment')->count(),
            'unpaid_requests' => DocumentRequest::whereDoesntHave('payment')
                ->whereHas('documentType', fn ($query) => $query->where('fee', '>', 0))
                ->whereIn('status', ['Approved', 'Ready'])
                ->count(),
        ];

        return view('documents.index', compact('documents', 'paymentStats'));
    }

    public function create()
    {
        $residents = Resident::all();
        $documentTypes = DocumentType::all();
        return view('documents.create', compact('residents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'document_type_id' => 'required|exists:document_types,id',
            'purpose' => 'required|string',
            'date_requested' => 'required|date',
        ]);

        $validated['request_number'] = 'DOC-' . strtoupper(uniqid());
        $validated['status'] = 'Pending';

        DocumentRequest::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document request created successfully');
    }

    public function show(DocumentRequest $document)
    {
        $document->load(['resident', 'documentType', 'payment', 'approvedBy', 'releasedBy']);
        return view('documents.show', compact('document'));
    }

    public function approve(DocumentRequest $document)
    {
        $document->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'date_ready' => now()->addDays($document->documentType->processing_days ?? 1),
        ]);

        return back()->with('success', 'Document request approved');
    }

    public function reject(DocumentRequest $document)
    {
        $document->update(['status' => 'Rejected']);
        return back()->with('success', 'Document request rejected');
    }

    public function release(DocumentRequest $document)
    {
        if ($document->documentType->fee > 0 && ! $document->payment) {
            return back()->with('error', 'Payment must be recorded before releasing this document.');
        }

        $document->update([
            'status' => 'Released',
            'released_by' => auth()->id(),
            'date_released' => now(),
        ]);

        return back()->with('success', 'Document released successfully');
    }

    public function edit(DocumentRequest $document)
    {
        $residents = Resident::orderBy('last_name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('documents.create', compact('document', 'residents', 'documentTypes'));
    }

    public function update(Request $request, DocumentRequest $document)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'document_type_id' => 'required|exists:document_types,id',
            'purpose' => 'required|string',
            'date_requested' => 'required|date',
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Processing,Ready,Released,Rejected',
        ]);

        $document->update($validated);

        return redirect()->route('documents.show', $document)->with('success', 'Document request updated successfully');
    }

    public function destroy(DocumentRequest $document)
    {
        if ($document->payment()->exists()) {
            return back()->with('error', 'Document request cannot be deleted because it already has a payment record.');
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document request deleted successfully');
    }
}
