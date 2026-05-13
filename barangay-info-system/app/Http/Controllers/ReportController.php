<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\DocumentRequest;
use App\Models\Blotter;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function population()
    {
        $data = DB::table('residents')
            ->join('households', 'residents.household_id', '=', 'households.id')
            ->join('puroks', 'households.purok_id', '=', 'puroks.id')
            ->select('puroks.name', DB::raw('count(*) as total'))
            ->groupBy('puroks.name')
            ->get();

        return view('reports.population', compact('data'));
    }

    public function financial()
    {
        $payments = Payment::with(['documentRequest', 'receivedBy'])->latest()->paginate(50);
        $totalRevenue = Payment::sum('amount');
        $totalPayments = Payment::count();
        $averagePayment = Payment::avg('amount') ?? 0;
        $todayRevenue = Payment::whereDate('payment_date', today())->sum('amount');
        $methodTotals = Payment::select('payment_method', DB::raw('count(*) as total_payments'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();
        $monthlyTotals = Payment::select(
                DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
                DB::raw('sum(amount) as total_amount'),
                DB::raw('count(*) as total_payments')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('reports.financial', compact(
            'payments',
            'totalRevenue',
            'totalPayments',
            'averagePayment',
            'todayRevenue',
            'methodTotals',
            'monthlyTotals'
        ));
    }

    public function documents()
    {
        $requests = DocumentRequest::with(['resident', 'documentType'])->latest()->paginate(50);

        return view('reports.documents', compact('requests'));
    }

    public function blotters()
    {
        $blotters = Blotter::with(['reportedBy', 'resolvedBy'])->latest()->paginate(50);

        return view('reports.blotters', compact('blotters'));
    }

    public function events()
    {
        $events = Event::with(['eventParticipants', 'createdBy'])->latest()->paginate(50);

        return view('reports.events', compact('events'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'population':
                $pdf = Pdf::loadView('reports.population-pdf', ['data' => $this->getPopulationData()]);
                return $pdf->download('Barangay_Population_Report_' . date('Y-m-d') . '.pdf');

            case 'financial':
                return $this->exportFinancialCsv();

            case 'documents':
                $pdf = Pdf::loadView('reports.documents-pdf', ['requests' => DocumentRequest::with(['resident', 'documentType'])->get()]);
                return $pdf->download('Barangay_Documents_Report_' . date('Y-m-d') . '.pdf');

            case 'blotters':
                $pdf = Pdf::loadView('reports.blotters-pdf', ['blotters' => Blotter::with(['reportedBy', 'resolvedBy'])->get()]);
                return $pdf->download('Barangay_Blotters_Report_' . date('Y-m-d') . '.pdf');

            case 'events':
                $pdf = Pdf::loadView('reports.events-pdf', ['events' => Event::with(['eventParticipants', 'createdBy'])->get()]);
                return $pdf->download('Barangay_Events_Report_' . date('Y-m-d') . '.pdf');

            default:
                return redirect()->back()->with('error', 'Report type not supported');
        }
    }

    private function getPopulationData()
    {
        return DB::table('residents')
            ->join('households', 'residents.household_id', '=', 'households.id')
            ->join('puroks', 'households.purok_id', '=', 'puroks.id')
            ->select('puroks.name', DB::raw('count(*) as total'))
            ->groupBy('puroks.name')
            ->get();
    }

    private function exportFinancialCsv()
    {
        $fileName = 'Barangay_Financial_Report_' . date('Y-m-d') . '.csv';
        $payments = Payment::with(['documentRequest', 'receivedBy'])->orderBy('payment_date', 'desc');

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['OR Number', 'Request Number', 'Amount', 'Payment Method', 'Reference Number', 'Payment Date', 'Received By']);

            $payments->chunk(100, function ($rows) use ($handle) {
                foreach ($rows as $payment) {
                    fputcsv($handle, [
                        $payment->or_number,
                        $payment->documentRequest->request_number ?? '',
                        $payment->amount,
                        $payment->payment_method,
                        $payment->reference_number,
                        $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '',
                        $payment->receivedBy->name ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
